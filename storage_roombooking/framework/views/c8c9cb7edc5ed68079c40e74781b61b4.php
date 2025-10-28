<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ยืนยันรหัส OTP - ระบบจองห้องออนไลน์ของมหาวิทยาลัย</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-image: linear-gradient(to bottom right,
                    rgba(137, 255, 166, 0.4),
                    rgba(160, 183, 245, 0.6),
                    rgba(245, 160, 234, 0.6)), url('<?php echo e(asset('images/bg-1.jpg')); ?>');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="bg-gray-900 bg-opacity-90 flex items-center justify-center min-h-screen px-4">
    <div
        class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-md transform transition-all hover:scale-[1.01] duration-300 ease-in-out">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">ยืนยันรหัส OTP</h1>

        <?php if(session('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('password.otp')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <!-- OTP Input -->
            <div class="mb-4">
                <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">กรอกรหัส OTP</label>
                <input id="otp" type="text" name="otp" required autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    placeholder="เช่น: 123456">

                <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Hidden Email (ถ้าจำเป็น) -->
            <input type="hidden" name="email" value="<?php echo e(old('email', session('email'))); ?>">

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition transform hover:shadow-md hover:-translate-y-0.5 duration-200 ease-in-out">
                ยืนยันรหัส
            </button>
        </form>

        <!-- Resend OTP -->
        <div class="mt-4 text-center">
            <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-blue-600 hover:text-blue-800 transition">
                ส่งรหัสใหม่
            </a>
        </div>
    </div>
</body>

</html>
<?php /**PATH /var/www/html/resources/views/verify_otp.blade.php ENDPATH**/ ?>