<?php
require_once '../../config/app.php';

if(isset($_SESSION['ADMIN_ID'])) {
    $admin_id = $_SESSION['ADMIN_ID'];

    $admin = R::load('admins', $admin_id);

    $broadcasts = [];

} else {
    // Redirect user to login
    header('location: login.php');
}

if (isset($_POST['BROADCAST'])) {
    $message = htmlspecialchars(strip_tags(trim($_POST['message'])));

    if (empty($message)) {

//        $_SESSION['HAS_ERROR'] = 'Broadcast message is required';

    }

}


?>


<?php require $base_url . 'app/includes/head.php'; ?>

<?php require $base_url . 'app/includes/nav.php'; ?>


<!-- <div class="container-minimized heading">
  <h1>Support</h1>
</div>/container-minimized
<div class="container-minimized">
  s
</div> -->

<div class="container">
    <h1 style="margin: 30px 0" class="text-5xl">
        <i class="fas fa-bullhorn"></i> Broadcast <small class="text-xl text-green-900">Send important updates to all users. Let's say a notice board. Currently all message are displayed to all users.</small>
        <span class="pull-right">
            <a href="javascript:void(0)" id="broadcastMessageModal" class="modal-open-off btn btn-primary btn-sm"><i class="fa fa-plus"></i> Broadcast a Message</a>
        </span>

        <div class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">

            <div class="modal-close absolute top-0 right-0 cursor-pointer flex flex-col items-center mt-4 mr-4 text-white text-sm z-50">
                <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                    <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                </svg>
                <span class="text-sm-09">(Esc)</span>
            </div>

            <!-- Add margin if you want to see some of the overlay behind the modal-->
            <div class="modal-content py-4 text-left px-6">
                <!--Title-->
                <div class="flex justify-between items-center pb-3">
                    <p class="text-4xl font-bold">Broadcast</p>

                    <div class="modal-close cursor-pointer z-50">
                        <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                        </svg>
                    </div>
                </div>

                <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">

                    <!--Body-->
                    <div class="text-xl"
                        <p class="block">You can only broadcast a message at a time</p>
                        <div class="form-group">
                            <textarea name="message" id="message" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <!--Footer-->
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="btn btn-success btn-sm mr-4" name="BROADCAST">Broadcast</button>
                        <a class="modal-close btn btn-danger btn-sm">Close</a>
                    </div>

                </form>

            </div>
        </div>
</div>


        <script>
            var openmodal = document.querySelectorAll('.modal-open')

            for (var i = 0; i < openmodal.length; i++) {
                openmodal[i].addEventListener('click', function(event){
                    event.preventDefault()
                    toggleModal()
                })
            }

            const overlay = document.querySelector('.modal-overlay')
            overlay.addEventListener('click', toggleModal)

            var closemodal = document.querySelectorAll('.modal-close')
            for (var i = 0; i < closemodal.length; i++) {
                closemodal[i].addEventListener('click', toggleModal)
            }

            document.onkeydown = function(evt) {
                evt = evt || window.event
                var isEscape = false
                if ("key" in evt) {
                    isEscape = (evt.key === "Escape" || evt.key === "Esc")
                } else {
                    isEscape = (evt.keyCode === 27)
                }
                if (isEscape && document.body.classList.contains('modal-active')) {
                    toggleModal()
                }
            };


            function toggleModal () {
                const body = document.querySelector('body')
                const modal = document.querySelector('.modal')
                modal.classList.toggle('opacity-0')
                modal.classList.toggle('pointer-events-none')
                body.classList.toggle('modal-active')
            }


        </script>

    </h1>
    <hr>
    <div class="panel panel-default" style="border: none">
        <div class="panel-body">
            <p>
                <?php if (isset($_SESSION['FLASH_USER_CREATED'])): ?>
            <div class="alert alert-success text-center">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <?php echo $_SESSION['FLASH_USER_CREATED']; ?>
                <?php unset($_SESSION['FLASH_USER_CREATED']); ?>
            </div>
            <?php endif; ?>
            <?php if (isset($flash_message)): ?>
                <div class="alert alert-success text-center">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <?php echo $flash_message; ?>
                </div>
            <?php endif; ?>
            </p>

            <div class="mt-20 text-5xl">
                <div class="flex flex-col">
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                        <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">

                            <?php if(count($broadcasts)): ?>
                                <table class="min-w-full">
                                    <thead>
                                    <tr>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            Name
                                        </th>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                                    </tr>
                                    </thead>
                                    <?php foreach ($broadcasts as $broadcast): ?>
                                        <tbody class="bg-white">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-xl leading-5 font-medium text-gray-900">
                                                            <?= $user->name; ?>
                                                        </div>
                                                        <div class="text-xl leading-5 text-gray-500 pt-2">
                                                            <?= $user->email; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <?php if($user->is_active): ?>
                                                    <span class="px-2 inline-flex text-xl leading-5 font-semibold rounded-sm bg-green-100 text-green-800 p-2">
                                                    Active
                                                </span>
                                                <?php else: ?>
                                                    <span class="px-2 inline-flex text-xl leading-5 font-semibold rounded-sm bg-red-100 text-red-800 p-2">
                                                    Inactive
                                                </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-xl leading-5 font-medium">
                                                <div>
                                               <span style="width: 20px;">
                                                   <a href="accounts.php?user=<?= $user->id; ?>" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150"><i class="fas fa-file-invoice-dollar" style="margin-right: 10px"></i> Accounts</a>
                                               </span>
                                                    <span>
                                                   <a href="edit_user.php?user=<?= $user->id; ?>" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150"><i class="fas fa-user-edit" style="margin-right: 10px"></i> Edit User</a>
                                               </span>
                                                    <span>
                                                   <a href="users.php?delete=<?= $user->id; ?>" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150"
                                                      onclick="return confirm('Do you want to delete this?')">
                                                       <i class="fas fa-user-minus" style="margin-right: 10px"></i>
                                                       Delete User
                                                   </a>
                                               </span>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    <?php endforeach; ?>


                                </table>

                            <?php else: ?>

                                <div class="text-center mt-32 text-gray-400 text-gray-600">
                                        <span class="text-5xl">
                                            ¯\_(ツ)_/¯
                                        </span>
                                    <p class="mb-32 mt-4 text-2xl">
                                        You don't have any broadcast message yet <br>
                                        Broadcast a message to get started
                                    </p>
                                </div>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <?php include $base_url . 'app/includes/footer.php'; ?>
