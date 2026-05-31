<?php

namespace App\Http\Controllers;

use App\Models\FavoriteBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    // Buscar livros na Open Library API:
    public function search(Request $request)
    {
        $termo = $request->query('q', 'the lord of the rings');
        
        $response = Http::get("https://openlibrary.org/search.json", [
            'q' => $termo,
            'limit' => 5
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Falha ao consultar a API externa'], 500);
        }

        $resultados = $response->json()['docs'] ?? [];

        $livros_processados = [];

        foreach ($resultados as $doc) {
            $coverId = $doc['cover_i'] ?? null;
            $livros_processados[] = [
                'open_library_id' => $doc['key'] ?? '',
                'title' => $doc['title'] ?? 'Sem título',
                'author' => $doc['author_name'][0] ?? 'Autor Desconhecido',
                'cover_url' => $coverId ? "https://covers.openlibrary.org/b/id/{$coverId}-M.jpg" : null
            ];
        }

        return view('search', ['books' => $livros_processados]);
    }

    // Favoritar:
    public function favoritar(Request $request)
    {
        if (!Auth::check()) { return redirect('/login'); }

        $dados = $request->validate([
            'open_library_id' => 'required|string',
            'title' => 'required|string',
            'author' => 'string|nullable',
            'cover_url' => 'string|nullable',
        ]);

        $dados['user_id'] = Auth::id();

        FavoriteBook::create($dados);

        return back()->with('sucesso', 'Livro adicionado aos favoritos!');
    }

    // Remover dos favoritos:
    public function removerFavorito($id)
    {
        if (!Auth::check()) { 
            return redirect('/login'); 
        }

        $favorito = FavoriteBook::where('id', $id)
                                ->where('user_id', Auth::id())
                                ->first();

        if ($favorito) {
            $favorito->delete();
            return back()->with('sucesso', 'Livro removido dos favoritos!');
        }

        return back()->with('erro', 'Livro não encontrado.');
    }

    // Favoritos do usuário logado:
    public function listarFavoritos()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Não autorizado'], 401);
        }

        $meusFavoritos = FavoriteBook::where('user_id', Auth::id())->get();
        return response()->json($meusFavoritos);
    }
}
