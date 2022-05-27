<?php require '../../../config/app.php'; ?>

<?php require $base_url . 'app/account/new/layouts/head.php'; ?>

<div>
    <!-- component -->
    <div class="w-full flex flex-wrap">

        <!-- Login Section -->
        <div class="w-full md:w-1/2 flex flex-col">

            <div class="flex justify-center md:justify-start pt-12 md:pl-12 md:-mb-24">
                <a href="#" class="bg-black text-white font-bold text-xl p-4">Logo</a>
            </div>

            <div class="flex flex-col justify-center md:justify-start my-auto pt-8 md:pt-0 px-8 md:px-24 lg:px-32">
                <p class="text-3xl">Online Realtime Balances and Transactions</p>
                <p>Please type your user ID and enter your password to get access.</p>
                <form class="flex flex-col pt-3 md:pt-8" action="login.php" method="post">
                    <div class="flex flex-col pt-4">
                        <label for="email" class="text-lg">User ID</label>
                        <input type="text" name="username" id="username" placeholder="User ID" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="flex flex-col pt-4">
                        <label for="password" class="text-lg">Password</label>
                        <input type="password" name="password" id="password" placeholder="**********" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <input type="submit" name="submit" value="Log In" class="bg-black text-white font-bold text-lg hover:bg-gray-700 p-2 mt-8">
                </form>
                <div class="text-center pt-12 pb-12">
<!--                    <p>Don't have an account? <a href="register.html" class="underline font-semibold">Register here.</a></p>-->
                </div>
                <div class="flex justify-between text-sm">
                    <div class="mb-5 mr-5">
                        <h1 class="text-xl text-indigo-600 mb-3"><span><i class="fas fa-shield-alt"></i></span> Security Tips</h1>
                        <p>Please note that International Maybank will NEVER ask you to provide your PIN (Personal Identification Numbers).</p>
                        <a href="index.html">Read more</a>
                    </div>

                    <div class="mb-5">
                        <h1 class="text-xl text-indigo-600 mb-3"><span><i class="fas fa-random"></i></span> Instant Transfers</h1>
                        <p>The fastest way to transfer money from your account to other bank account.</p>
                        <a href="#">Try it today</a>
                    </div>
                </div>
            </div>



        </div>

        <!-- Image Section -->
        <div class="w-1/2 shadow-2xl">
            <img class="object-cover w-full h-screen hidden md:block" src="https://images.unsplash.com/photo-1559589689-577aabd1db4f?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2250&q=80">
        </div>
    </div>



<?php require $base_url . 'app/account/new/layouts/footer.php'; ?>
