<div class="md:w-full mt-10">
    <h3 class="mb-3 sub-heading">Transactions</h3>
    <div class="flex flex-col rounded-md mr-5" style="background-color: #F9F9F9">

        <!-- component -->
        <?php if(!empty($transactions)): ?>

            <table class="min-w-full leading-normal">
            <thead>
            <tr>
                <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" style="background-color: #f9f9f9">
                    Number
                </th>
                <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" style="background-color: #f9f9f9">
                    Transfer date
                </th>
                <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" style="background-color: #f9f9f9">
                    Status
                </th>
                <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" style="background-color: #f9f9f9">
                    Transfer from
                </th>
                <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" style="background-color: #f9f9f9">
                    Amount
                </th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                <p class="text-gray-900 whitespace-no-wrap"> <?php echo $transaction->number; ?> </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                <p class="text-gray-900 whitespace-no-wrap"> <?php echo $transaction->transfer_date; ?> </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                <i class="fas fa-business-time text-xs mr-2 text-gray-500"></i>
                                <?php if($transaction->completed): ?>
                                    <span class="text-sm font-semibold text-green-600">Completed</span>
                                <?php else: ?>
                                    <span class="text-sm font-semibold text-yellow-700">
                                        <?php if($user->id == 26): ?>
                                            <?php echo 'Cancelled'; ?>
                                        <?php else: ?>
                                            <?php echo 'Pending'; ?>
                                            <span class="block">
                                                <?php if($user->id == 4): ?>
                                                    <span class="text-red-600">Please provide  tax clearance fee to complete order</span>
                                                <?php endif; ?>
                                            </span>
                                        <?php endif; ?>
                                    </span>
                                <?php endif; ?>
                            </p>
                            <p>
<!--                                Monthly-->
                            </p>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                <?php
                                    // echo $transaction->from;
                                    //$get_transfered_from_account = R::findOne('accounts', 'id = ?', [$transaction->from]);
                                    //$user_account = R::findOne('users', 'id = ?', [$get_transfered_from_account->id]);
                                    //echo $user_account->name . '<span class="font-semibold"> (..' . substr($get_transfered_from_account->number,-4) . ')</span>';
                                    if($transaction->id != 8) {
                                        echo "Self Deposit (Selbsteinzahlung)";
                                    } else  {
                                        echo "Ali Farid Alkatib (Internal Transfer)";
                                    }
                                ?>
                            </p>
                        </td>
                        
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block py-1 font-semibold text-green-900 leading-tight">
                            <span class="relative"><?php echo interpret_currency($adminProfile->currency) . ' ' . number_format($transaction->amount, 2); ?></span>
                        </span>
                        </td>
                    </tr>
            <?php endforeach; ?>

            </tbody>
            </table>

        <?php else: ?>
            <div class="p-10">
                You have no transactions yet
            </div>
        <?php endif; ?>

    </div>
</div>