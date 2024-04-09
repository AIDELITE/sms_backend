<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <title>uccfstext - Reset password</title>
</head>

<body class="h-screen w-screen">

    <div class="py-20 bg-gray-200 rounded mx-auto flex flex-col justify-center items-center">
    <h2 class="my-6 text-center text-2xl font-extrabold text-gray-900">
        Reset password
      </h2>
        <form class="mx-auto" action="<?php echo site_url('auth/reset_password') ?>" method="post">
        <input type="hidden" value="<?php echo $user_id ?>" name="user_id">
        <input type="hidden" value="<?php echo $token ?>" name="token">
        <div class="flex flex-col">
            <label for="new_password">New Password</label>
            <input class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" type="password" name="new_password" minlength="6" required>
        </div>
        <div class="flex flex-col">
            <label for="confirm_new_password">Confirm new password</label>
            <input class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" type="password" name="confirm_new_password" minlength="6" required>
        </div>
        <div class="flex justify-center my-2">
            <button class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" type="submit">Reset Password</button>
        </div>
        </form>

    </div>

</body>

</html>