<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><title>Buscar Livros</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 p-6">
    <nav class="max-w-5xl mx-auto flex justify-between items-center bg-white p-4 rounded-lg shadow-sm mb-6">
        <span class="font-bold text-lg text-gray-700">Olá, {{ Auth::user()->name ?? 'Visitante' }}</span>
        <div class="space-x-4">
            <a href="/books/my-favorites" class="text-blue-600 font-medium hover:underline">⭐ Meus Favoritos</a>
            <a href="/logout" class="text-red-500 hover:underline">Sair</a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Pesquisar Livros</h1>
        
        <form action="/search-books" method="GET" class="flex gap-2 mb-8">
            <input type="text" name="q" placeholder="Digite o título do livro..." value="{{ request('q') }}" class="flex-1 p-3 border rounded-lg shadow-sm">
            <button type="submit" class="bg-blue-600 text-white px-6 rounded-lg font-medium">Buscar</button>
        </form>

        @if(session('sucesso')) <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('sucesso') }}</div> @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6">
            @foreach($books as $book)
                <div class="bg-white p-4 rounded-lg shadow-sm flex flex-col justify-between">
                    <div>
                        <img src="{{ $book['cover_url'] ?? 'https://via.placeholder.com/150x200?text=Sem+Capa' }}" class="w-full h-48 object-cover rounded mb-2">
                        <h3 class="font-bold text-gray-800 text-sm line-clamp-2">{{ $book['title'] }}</h3>
                        <p class="text-gray-600 text-xs mb-4">{{ $book['author'] }}</p>
                    </div>
                    <form action="/books/favorite" method="POST">
                        @csrf
                        <input type="hidden" name="open_library_id" value="{{ $book['open_library_id'] }}">
                        <input type="hidden" name="title" value="{{ $book['title'] }}">
                        <input type="hidden" name="author" value="{{ $book['author'] }}">
                        <input type="hidden" name="cover_url" value="{{ $book['cover_url'] }}">
                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-2 rounded transition">⭐ Favoritar</button>
                    </form>
                </div>
            @endforeach
        </div>
    </main>
</body>
</html>