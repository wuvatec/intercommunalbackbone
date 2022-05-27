<div class="mt-5">

    <?php if (isset($success)): ?>

        <div class="w-full mb-5 bg-green-100 text-green-700 px-3 py-5 font-semibold">
            <?php echo "<i class=\"fas fa-check-circle\"></i> " . $success; ?>
        </div>

    <?php endif; ?>

    <?php if(isset($_SESSION['DELETED'])): ?>

        <div class="w-full mb-5 bg-green-100 text-green-700 px-3 py-5 font-semibold">
            <?php
                echo "<i class=\"fas fa-check-circle\"></i> " . $_SESSION['DELETED'];
                unset($_SESSION['DELETED']);
            ?>
        </div>

    <?php endif; ?>

    <?php if(isset($_SESSION['ERROR'])): ?>

        <div class="w-full mb-5 bg-red-100 text-red-700 px-3 py-5 font-semibold">
            <?php
                echo "<i class=\"fas fa-times-circle\"></i> " . $_SESSION['ERROR'];
                unset($_SESSION['ERROR']);
            ?>
        </div>

    <?php endif; ?>

    <?php if(isset($_SESSION['CREATED'])): ?>

        <div class="w-full mb-5 bg-green-100 text-green-700 px-3 py-5 font-semibold">
            <?php
                echo "<i class=\"fas fa-check-circle\"></i> " . $_SESSION['CREATED'];
                unset($_SESSION['CREATED']);
            ?>
        </div>

    <?php endif; ?>

</div>