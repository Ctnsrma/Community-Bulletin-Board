<?php
session_start();
if (!isset($_SESSION['Username'])) {
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-r from-slate-900 to-slate-700 min-h-screen">
    <h1 class="text-center py-8 font-bold text-4xl text-[#FFFFFFB3]">Hello <?php echo $_SESSION['Username']; ?></h1>

    <header class="bg-gradient-to-r from-blue-900 text-yellow-400 via-blue-600 to-blue-900 py-6 relative">
        <div class="container mx-auto text-center flex justify-between items-center px-10">
            <a href="index.php">
                <i class="fa-brands fa-files-pinwheel text-6xl hover:text-white cursor-pointer"></i>
            </a>
            <div>
                <h1 class="text-5xl font-bold">Community Bulletin Board</h1>
                <p class="mt-2 text-lg">Share updates with your community</p>
            </div>
            <div class="relative">
                <i id="profileIcon" class="fa-solid fa-user text-4xl hover:text-white cursor-pointer"></i>
                <div id="profileDropdown" class="absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg hidden z-50 p-4">
                    <h3 class="font-bold text-lg text-gray-800 mb-2"><?php echo $_SESSION['Username']; ?>'s Posts</h3>
                    <ul id="userPostsList" class="text-sm max-h-60 overflow-y-auto text-gray-700 space-y-1"></ul>
                    <a href="logout.php"
                        class="mt-4 inline-block bg-blue-800 hover:bg-blue-900 text-white px-4 py-2 rounded-md text-center w-full">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto py-10 px-6 md:px-20">
        <section class="bg-white shadow-lg rounded-lg p-8 mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Create a Post</h2>
            <form id="postForm" class="space-y-4">
                <div>
                    <label for="title" class="block text-gray-700">Title</label>
                    <input type="text" id="title" name="title" required
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="content" class="block text-gray-700">Message</label>
                    <textarea id="content" name="content" required rows="5"
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label for="author" class="block text-gray-700">Your Name</label>
                    <input type="text" id="author" name="author" required
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="<?php echo $_SESSION['Username']; ?>">
                </div>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Submit Post</button>
            </form>
            <p id="formError" class="text-red-500 mt-2 hidden">Please fill out all fields correctly.</p>
        </section>

        <section id="posts" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        </section>
    </main>

    <script>
        const currentUser = "<?php echo $_SESSION['Username']; ?>";

        function loadPosts() {
            const posts = JSON.parse(localStorage.getItem('posts')) || [];
            const postsContainer = document.getElementById('posts');
            const userPostsList = document.getElementById('userPostsList');
            postsContainer.innerHTML = '';
            userPostsList.innerHTML = '';

            const colors = [
                'bg-yellow-100',
                'bg-blue-100',
                'bg-green-100',
                'bg-purple-100',
                'bg-pink-100',
                'bg-red-100',
                'bg-indigo-100',
                'bg-orange-100',
                'bg-emerald-100'
            ];

            posts.forEach((post, index) => {
                const colorClass = colors[index % colors.length];

                const postElement = document.createElement('div');
                postElement.className = `${colorClass} shadow-lg rounded-lg p-6 transition-all duration-300`;

                postElement.innerHTML = `
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">${post.title}</h3>
                    <p class="text-gray-600 mb-2">${post.content}</p>
                    <p class="text-sm text-gray-500 mb-4">Posted by ${post.author} on ${post.date}</p>

                    <div class="flex items-center space-x-4 mb-2">
                        <button onclick="likePost(${index})" class="text-blue-600 hover:text-blue-800">
                            👍 Like (<span id="like-count-${index}">${post.likes || 0}</span>)
                        </button>
                        <button onclick="toggleCommentBox(${index})" class="text-green-600 hover:text-green-800">
                            💬 Comment
                        </button>
                    </div>

                    <div id="comment-box-${index}" class="hidden">
                        <textarea id="comment-input-${index}" class="w-full border rounded p-2 mb-2" placeholder="Write a comment..."></textarea>
                        <button onclick="addComment(${index})" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Post</button>
                    </div>

                    <div id="comments-${index}" class="mt-4 space-y-1">
                        ${(post.comments || []).map(c => `<p class="text-gray-700 text-sm border-t pt-1">${c}</p>`).join('')}
                    </div>
                `;

                postsContainer.appendChild(postElement);

                // Add to user profile dropdown if author matches
                if (post.author === currentUser) {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <div class="flex justify-between items-center">
                            <span>${post.title}</span>
                            <button onclick="deletePost(${index})" class="text-red-500 hover:underline">Delete</button>
                        </div>
                    `;
                    userPostsList.appendChild(li);
                }
            });
        }

        function savePost(post) {
            const posts = JSON.parse(localStorage.getItem('posts')) || [];
            posts.push(post);
            localStorage.setItem('posts', JSON.stringify(posts));
        }

        function deletePost(index) {
            const posts = JSON.parse(localStorage.getItem('posts')) || [];
            posts.splice(index, 1);
            localStorage.setItem('posts', JSON.stringify(posts));
            loadPosts();
        }

        function likePost(index) {
            const posts = JSON.parse(localStorage.getItem('posts')) || [];
            posts[index].likes = (posts[index].likes || 0) + 1;
            localStorage.setItem('posts', JSON.stringify(posts));
            document.getElementById(`like-count-${index}`).innerText = posts[index].likes;
        }

        function toggleCommentBox(index) {
            const box = document.getElementById(`comment-box-${index}`);
            box.classList.toggle('hidden');
        }

        function addComment(index) {
            const commentInput = document.getElementById(`comment-input-${index}`);
            const comment = commentInput.value.trim();
            if (comment.length === 0) return;

            const posts = JSON.parse(localStorage.getItem('posts')) || [];
            posts[index].comments = posts[index].comments || [];
            posts[index].comments.push(comment);
            localStorage.setItem('posts', JSON.stringify(posts));
            commentInput.value = '';
            loadPosts();
        }

        document.getElementById('postForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();
            const author = document.getElementById('author').value.trim();
            const errorMsg = document.getElementById('formError');

            if (title.length < 3 || content.length < 10 || author.length < 2) {
                errorMsg.textContent = 'Title must be 3+ characters, message 10+ characters, name 2+ characters.';
                errorMsg.classList.remove('hidden');
                return;
            }

            const post = {
                title,
                content,
                author,
                date: new Date().toLocaleString(),
                likes: 0,
                comments: []
            };

            savePost(post);
            this.reset();
            errorMsg.classList.add('hidden');

            loadPosts();
        });

        document.getElementById('profileIcon').addEventListener('click', () => {
            document.getElementById('profileDropdown').classList.toggle('hidden');
        });

        window.onload = loadPosts;
    </script>
</body>

</html>
