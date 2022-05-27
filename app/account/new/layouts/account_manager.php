<div class="flex flex-row justify-between">
    <div class="balance-overview">
        <div class="flex flex-row">
            <div class="flex-shrink-0 h-10 w-10 mr-5">
                <img class="h-10 w-10 rounded-full" 
                src="https://images.unsplash.com/photo-1462206092226-f46025ffe607?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=2299&q=80" alt="" />
            </div>
            <div>
                <div class="sub-heading">
                    Your Account Manager
                </div>
                <div class="">
                   
                    <?php
                    
                        if($user->id == 26) {
                            echo "Steven Delroy";
                        } else {
                            echo "Lothar Neuer";
                        }
                    
                    ?>
                    <a
                        href="messages.php?account_manager=<?php echo strtoupper('989000292__' . str_replace(' ', '_', $user->name)); ?>"
                        class="ml-3 text-pink-600">
                        <i class="fas fa-mail-bulk"></i> <small>Send a message </small>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col text-right">
        <div class="">
            <span class="text-gray-600">Last access date:</span>
            <span class="text-gray-600 font-semibold">
                <?php echo (!empty($user->last_login))? substr($user->last_login, 0, 10) : 'Now'; ?>
            </span>
        </div>
        <div class="">
            <span class="text-gray-600">At:</span> <span class="text-gray-600 font-semibold">
                <?php echo (!empty($user->last_login))? substr($user->last_login, -8) : 'Now'; ?>
            </span>
        </div>
    </div>
</div>