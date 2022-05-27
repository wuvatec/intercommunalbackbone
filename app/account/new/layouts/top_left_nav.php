<div class="mr-10 text-2xl">
    <div class="inline-block hidden animate__animated animate__bounce" id="searchForm">
        <input class="px-4 text-gray-800 bg-gray-200 focus:outline-none mr-3 text-sm py-2"
               style="width: 400px"
               type="text"
               name="search"
               placeholder="Search [Accounts, Transactions, Messages, etc]">
    </div>

    <div class="inline-block">
        <a href="javascript:void(0)" id="searchBtn" onclick="toggleSearchForm()">
            <i class="fas fa-search"></i>
        </a>
    </div>
</div>
<div class="text-2xl mr-3">
    <a href="messages.php">
        <i class="fas fa-bell"></i>
    </a>
</div>

<style>
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 200px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        padding: 12px 16px;
        z-index: 1;
        /*top: 50px;*/
        /*right: 5px;*/
        right: 10px;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>

<div class="text-xl dropdown">

    <div class="dropdown">
        <span class="border-b border-gray-200">
            <a href="javascript:void(0)"><i class="fas fa-user-circle"></i> <span class="text-xl"><?php echo $user->name; ?></span> </a>
        </span>
        <div class="dropdown-content">
            <ul>
                <li class="text-sm mb-3">
                    <a class="hover:text-pink-600 py-3 w-full text-left text-gray-600 mt-20 font-semibold" href="summary.php">
                        <i class="fa fa-user-cog pr-4 pt-1"></i>Account Settings
                    </a>
                </li>
                <li class="text-sm mb-3">
                    <a class="hover:text-pink-600 py-3 w-full text-left text-gray-600 mt-20 font-semibold" href="password.php">
                        <i class="fa fa-unlock-alt pr-4 pt-1"></i>Change Password
                    </a>
                </li>
                <li class="text-sm">
                    <a class="hover:text-pink-600 py-3 w-full text-left text-gray-600 mt-20 font-semibold" href="logout.php">
                        <i class="fa fa-sign-out pr-4 pt-1"></i>Log out
                    </a>
                </li>
            </ul>
        </div>
    </div>

</div>


<script>

    function toggleSearchForm() {
        let searchForm = document.getElementById("searchForm");
        searchForm.classList.toggle("hidden");
    }

</script>