<?php
require_once '../../config/app.php';

if(isset($_SESSION['ADMIN_ID'])) {
    $admin_id = $_SESSION['ADMIN_ID'];

    $user = R::load('admins', $admin_id);

    if (isset($_GET['account'])) {
        $account_id = $_GET['account'];
        $transactions = R::findAll('transactions', 'account_id = ? ORDER BY transfer_date DESC', [$account_id]);
    }

    if (isset($_GET['approve'])) {

        $transaction = R::load('transactions', $_GET['approve']);
        $sender = R::findOne('accounts', 'id = ?', [$transaction->account_id]);

        $beneficiary = R::findOne('accounts', 'id = ?', [$transaction->to]);

        if(!empty($transaction->to) && strlen($transaction->to) < 4) {

//            $beneficiary = R::findOne('accounts', 'id = ?', [$transaction->to]);

            // debit sending account
            $sender->pending_balance = $sender->pending_balance - $transaction->amount;
            R::store($sender);

            // credit receiving account
            $beneficiary->balance = $beneficiary->balance + $transaction->amount;

            // Log new transfer for beneficiary
            $transfer = R::dispense('transactions');
            $transfer->number = $transfer->number = substr($sender->id . md5(time()), 0, 12);
            $transfer->user_id = $transaction->user_id;
            $transfer->account_id = $beneficiary->id;
            $transfer->amount = $transaction->amount;
            $transfer->from = $sender->id;
            $transfer->completed = 1;
            $transfer->credit = 1;
            $transfer->debit = 0;
            $transfer->to = $beneficiary->id;
            $transfer->transfer_date = $transaction->transfer_date;
            $transfer->memo = $transaction->memo;

            R::store($transfer);
            R::store($beneficiary);


            // set status to complete
            $transaction->completed = 1;
            R::store($transaction);

            $_SESSION['FLASH_USER_CREATED'] = 'Transfer to ' . $beneficiary->number . ' successfully completed';
            header('location: transactions.php?account=' . $account_id);
            exit();
        }

        if(!empty($transaction->to) && strlen($transaction->to) > 4) {

            $beneficiary = R::findOne('accounts', 'number = ?', [$transaction->to]);

            // debit sending account
            $sender->pending_balance = $sender->pending_balance - $transaction->amount;
            R::store($sender);

            // credit receiving account
            $beneficiary->balance = $beneficiary->balance + $transaction->amount;

            // Log new transfer for beneficiary
            $transfer = R::dispense('transactions');
            $transfer->number = $transfer->number = substr($sender->id . md5(time()), 0, 12) . '_' . $transaction->number;
            $transfer->user_id = $transaction->user_id;
            $transfer->account_id = $beneficiary->id;
            $transfer->amount = $transaction->amount;
            $transfer->from = $sender->id;
            $transfer->completed = 1;
            $transfer->credit = 1;
            $transfer->debit = 0;
//            $transfer->to = $beneficiary->id;
            $transfer->transfer_date = $transaction->transfer_date;
            $transfer->memo = $transaction->memo;

            R::store($transfer);
            R::store($beneficiary);


            // set status to complete
            $transaction->completed = 1;
            R::store($transaction);

            $_SESSION['FLASH_USER_CREATED'] = 'Transfer to ' . $beneficiary->number . ' successfully completed';
            header('location: transactions.php?account=' . $account_id);
            exit();

        }

        if(!empty($transaction->beneficiary) && $transaction->to == null) {

            // debit sending account
            $sender = R::findOne('accounts', 'id = ?', [$transaction->from]);
            $sender->pending_balance = $sender->pending_balance - $transaction->amount;
            R::store($sender);
            // credit sending account

            // set status
            $transaction->completed = 1;
            R::store($transaction);

            $beneficiary = R::findOne('beneficiaries', 'id = ?', [$transaction->beneficiary]);
            $_SESSION['FLASH_USER_CREATED'] = 'Transfer to ' . $beneficiary->name . ' successfully completed';
            header('location: transactions.php?account=' . $account_id);
            exit();
        }
    }

    if (isset($_GET['reverse'])) {
        $transaction = R::load('transactions', $_GET['reverse']);
        $sender = R::findOne('accounts', 'id = ? ', [$transaction->from]);
        $sender->pending_balance = $sender->pending_balance - $transaction->amount;
        R::store($sender);

        $transaction->completed = 1;
        $transaction->reversed = 1;
        R::store($transaction);

        $_SESSION['FLASH_USER_CREATED'] = 'Transfer has been revered';
        header('location: transactions.php?account=' . $account_id);
        exit();
    }

} else {
    // Redirect user to login
    header('location: login.php');
}

function beneficiary($beneficiary) {
    return R::findOne('accounts', 'number = ?', [$beneficiary]);
}


?>

<?php require $base_url . 'app/includes/head.php'; ?>

<?php require $base_url . 'app/includes/nav.php'; ?>


<div class="container">
    <h1 style="margin: 30px 0">
        <i class="fas fa-user-lock"></i> Transactions
        <span class="pull-right">
                <a href="new_transaction.php" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> ADD TRANSACTION</a>
            </span>
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

                            <?php if(count($transactions)): ?>
                                <table class="min-w-full">
                                    <thead>
                                    <tr>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            Transaction
                                        </th>
                                        <th class="px-6 py-5 border-b border-gray-200 bg-gray-50 text-left text-xl leading-4 font-medium text-gray-700 uppercase tracking-wider p-4">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                                    </tr>
                                    </thead>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tbody class="bg-white">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-xl leading-5 font-medium text-gray-900">
                                                            <?= $transaction->number; ?>
                                                        </div>
                                                        <div class="text-xl leading-5 text-gray-500 pt-2">
                                                            <?= $transaction->transfer_date; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <?php if($transaction->completed): ?>
                                                    <span class="px-2 inline-flex text-xl leading-5 font-semibold rounded-sm bg-green-100 text-green-800 p-2">
                                                    Completed
                                                </span>
                                                <?php else: ?>
                                                    <span class="px-2 inline-flex text-xl leading-5 font-semibold rounded-sm bg-red-100 text-red-800 p-2">
                                                    Pending
                                                </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-xl leading-5 font-medium">
                                                <div>
                                                  <?php if(!$transaction->completed): ?>
                                                      <span style="width: 20px;">
                                                       <a onclick="return confirm('Do you want to approve transfer?')" href="transactions.php?account=<?= $account_id ?>&approve=<?= $transaction->id; ?>" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150"><i class="fas fa-check" style="margin-right: 10px"></i> Approve</a>
                                                   </span>
                                                      <span>
                                                       <a onclick="return confirm('Do you want to reverse transfer?')" href="transactions.php?account=<?= $account_id ?>&reverse=<?= $transaction->id; ?>" class="inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150"><i class="fas fa-history" style="margin-right: 10px"></i> Reverse</a>
                                                   </span>
                                                  <?php else: ?>
                                                    <span class="text-green-600"><i class="fas fa-check"></i></span>
                                                  <?php endif; ?>
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
                                        You don't have any users yet <br>
                                        Add a user to get started
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
