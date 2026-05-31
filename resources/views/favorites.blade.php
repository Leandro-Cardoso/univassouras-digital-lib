<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Meus Favoritos</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Meus Livros Favoritos ⭐</h1>
            <a href="/search-books" class="bg-gray-600 text-white px-4 py-2 rounded">⬅ Voltar para Busca</a>
        </div>

        @if(session('sucesso')) <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('sucesso') }}</div> @endif
        @if(session('erro')) <div class="bg-red-100 text-red-800 p-3 rounded mb-4">{{ session('erro') }}</div> @endif

        @if($favorites->isEmpty())
            <div class="bg-white p-8 rounded-lg text-center text-gray-500">Você ainda não favoritou nenhum livro.</div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6">
                @foreach($favorites as $fav)
                    <div class="bg-white p-4 rounded-lg shadow-sm flex flex-col justify-between">
                        <div>
                            <img src="{{ $fav->cover_url ?? 'https://via.placeholder.com/150x200?text=Sem+Capa' }}" class="w-full h-48 object-cover rounded mb-2">
                            <h3 class="font-bold text-gray-800 text-sm line-clamp-2">{{ $fav->title }}</h3>
                            <p class="text-gray-600 text-xs mb-4">{{ $fav->author }}</p>
                        </div>
                        
                        <form action="/books/favorite/{{ $fav->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-2 rounded transition">
                                🗑 Remover
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>