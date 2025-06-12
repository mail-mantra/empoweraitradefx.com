<?php
$apiKey = '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';

$content = <<<TEXT
EmpowerAITrade is an international consortium of AI traders who collaborate with some of the world’s leading trading analysts and experts. These collaborations enable the company to perform large-scale forex and cryptocurrency trading around the clock. EmpowerAITrade uses advanced AI bots and real-time market data to create small trading clusters. These clusters may hold positions for a few seconds or several months, depending on the strategy, all aimed at maximizing profit.

The company is actively engaged in marketing and promotional activities to expand its reach and customer base. In India, promotions are carried out through various methods, including direct promoters, marketing agencies, and multi-level marketing (MLM) teams. The Indian division is led by Mr. Tapash Kumar Singha, also known as T. K. Singha, and has a dedicated in-house trading team for various market segments.

MLM Structure and Investment Plans

Anyone can join EmpowerAITrade through an invitation link shared by an existing member. All financial operations are conducted in USD. Members can fund their accounts either through transfers from other members or by depositing USDT via the BEP-20 blockchain. After logging in, users can deposit cryptocurrency by scanning a QR code and submitting their transaction details. The system automatically verifies and credits the balance to the Fund Wallet.

Fund Wallet: Used exclusively for investments.

Investment Options:

Community Trade
- Investment range: \$50 to \$500
- Guaranteed Return: 5% monthly
- Maximum ROI: 300% (3x the investment)
- Profits are credited to the Profit Wallet
- Once the 300% return is achieved, further ROI stops, and a reinvestment must be made using the Fund Wallet.

Live Trade
- Minimum investment: \$1,000, in multiples of \$1,000
- Guaranteed Return: 10% monthly
- Returns are credited to the Profit Wallet at the end of each month.

Referral & Bonus Structure

All referral and performance-based incomes are credited to the Working Wallet. There are five types of referral bonuses:
- Community Trade Level Bonus
- Live Trade Bonus
- Performance Bonus
- Reward Bonus
- Royalty Bonus

Community Trade Level Bonus (Details):

This bonus is earned from the Community Trade investments of referred members (ranging from \$50 to \$500). The referring member earns a monthly bonus based on the investment amount, calculated daily on a pro-rata basis, and paid across 30 levels:
- Level 1: 1.00% monthly
- Level 2 to 5: 0.25% monthly
- Level 6 to 10: 0.20% monthly
- Level 11 to 30: 0.10% monthly

Telegram Integration

EmpowerAITrade offers a Telegram Bot and Telegram Channel for enhanced user experience. After successfully logging into the dashboard, users can link their Telegram accounts directly to the bot. The bot provides real-time access to personalized account data and responds to specific commands via a user-friendly menu and keyword system.

Available Telegram Bot Keywords:
- My Intro: Retrieves the name and ID of your introducer.
- Password: Retrieves your current password securely.
- Remove My Telegram: Disconnects your Telegram account from the software.
- Last Txn Fund: Displays your latest transaction in the Fund Wallet.
- Last Txn Working: Displays your latest transaction in the Working Wallet.
- Last Txn Profit: Displays your latest transaction in the Profit Wallet.

- Fund: View Current Fund Wallet Balance.
- Working: View Current Working Wallet Balance.
- Profit: View My Profit or Income.
- Business: View My Business.
- Team Count: View My Team Count or Team Size.
- Sponsor Count: View Sponsor Count. 
- Self Investment: View My Investment.
- My Rank: View My Own Rank.
- Profile : View My Profile.
- Referral Link : View My Referral Link.
- Join Our Channel : To The Join Telegram Channel.

TEXT;




$messages = [
    ["role" => "system", "content" => $content],
    ["role" => "assistant", "content" => $assistant_content],
    ["role" => "user", "content" => $message]
];





// Create payload
$data = [
    'model' => 'gpt-3.5-turbo', // or 'gpt-3.5-turbo'
    'messages' => $messages,
    'temperature' => 0.7
];


$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

// Handle errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    $responseData = json_decode($response, true);
    $ai_result = $responseData['choices'][0]['message']['content'] ?? 'No response';
}

curl_close($ch);
?>
