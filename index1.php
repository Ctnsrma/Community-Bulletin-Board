<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-slate-900 to-slate-700 min-h-screen">
    <h1 class="text-center py-8 font-bold text-4xl text-[#FFFFFFB3]">Welcome</h1>

    <header class="bg-gradient-to-r from-blue-900 via-blue-600 to-blue-900 text-yellow-400 py-6">
        <div class="container mx-auto text-center flex justify-between">
            <a href="index1.php">
                <i class="fa-brands fa-files-pinwheel text-6xl px-8 py-2 hover:text-white cursor-pointer"></i>
            </a>
            <div>
                <h1 class="text-5xl font-bold">Community Bulletin Board</h1>
                <p class="mt-2 text-lg">Share updates with your community</p>
            </div>
            <div class="flex gap-2 px-8 items-center text-white">
                <a href="sign.php" class="underline">Sign Up</a> | 
                <a href="login.php" class="underline">Log In</a>
            </div>
        </div>
    </header>

    <main class="container mx-auto py-10 px-20">
        <section class="bg-white shadow-lg rounded-lg p-8 mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Create a Post</h2>

            <!-- Form (disabled for non-logged-in users) -->
            <form id="postForm" class="space-y-4 opacity-50 pointer-events-none">
                <div>
                    <label for="title" class="block text-gray-700">Title</label>
                    <input type="text" id="title" name="title" required
                           class="w-full p-2 border rounded-md bg-gray-100 cursor-not-allowed" disabled>
                </div>
                <div>
                    <label for="content" class="block text-gray-700">Message</label>
                    <textarea id="content" name="content" required rows="5"
                              class="w-full p-2 border rounded-md bg-gray-100 cursor-not-allowed" disabled></textarea>
                </div>
                <div>
                    <label for="author" class="block text-gray-700">Your Name</label>
                    <input type="text" id="author" name="author" required
                           class="w-full p-2 border rounded-md bg-gray-100 cursor-not-allowed" disabled>
                </div>
                <button type="submit"
                        class="bg-blue-400 text-white px-4 py-2 rounded-md cursor-not-allowed" disabled>
                    Submit Post
                </button>
            </form>
            <p class="text-red-600 mt-4 text-center">
                You must <a href="login.php" class="underline text-blue-700">log in</a> or 
                <a href="sign.php" class="underline text-blue-700">sign up</a> to create a post.
            </p>
        </section>
        <section id="posts" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></section>
    </main>
    <script>
        function loadPosts() {
            const posts = JSON.parse(localStorage.getItem('posts')) || [];
            const postsContainer = document.getElementById('posts');
            postsContainer.innerHTML = '';

            posts.forEach(post => {
                const postElement = document.createElement('div');
                postElement.className = 'bg-white shadow-lg rounded-lg p-6';
                postElement.innerHTML = `
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">${post.title}</h3>
                    <p class="text-gray-600 mb-4">${post.content}</p>
                    <p class="text-sm text-gray-500">Posted by ${post.author} on ${post.date}</p>
                `;
                postsContainer.appendChild(postElement);
            });
        }

        window.onload = loadPosts;
    </script>
</body>
</html>
