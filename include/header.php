<?php
ob_start();
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/tooth/');
session_start();
require_once(ROOT_PATH ."/config/db.php");

    $user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';


?>

<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DentalCare test</title>
    <!-- تضمين مكتبة Tailwind وخط Cairo -->
<link href="/tooth/output.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="font-cairo bg-slate-50 overflow-x-hidden
             [&::-webkit-scrollbar]:w-2
             [&::-webkit-scrollbar-track]:bg-slate-100
             [&::-webkit-scrollbar-thumb]:bg-sky-400
             [&::-webkit-scrollbar-thumb]:rounded-full
             hover:[&::-webkit-scrollbar-thumb]:bg-sky-500">
<!-- HEADER -->
<header class="fixed top-0 right-0 left-0 z-50 bg-white/70 backdrop-blur-xl border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            <!-- LOGO -->
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-3xl bg-gradient-to-l from-sky-500 to-sky-600 flex items-center justify-center text-white text-2xl shadow-xl shadow-sky-200">
                    🦷
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900">DentalCare</h1>
                    <p class="text-sm text-slate-500">Smart Dental Clinic</p>
                </div>
            </div>

            <!-- NAV -->
            <nav class="hidden lg:flex items-center gap-8 text-slate-700 font-bold">
                <a href="index.php" class="hover:text-sky-500 transition">الرئيسية</a>
                <a href="index.php#services" class="hover:text-sky-500 transition">الخدمات</a>
                <a href="index.php#about" class="hover:text-sky-500 transition">عن العيادة</a>
                <a href="index.php#reviews" class="hover:text-sky-500 transition">آراء المرضى</a>
                <a href="index.php#faq" class="hover:text-sky-500 transition">الأسئلة الشائعة</a>
                <a href="index.php#contact" class="hover:text-sky-500 transition">تواصل معنا</a>
                    <?php if ($user_role === 'user'){ ?>
                <a href="/tooth/user/booking.php" class="hover:text-sky-500 transition">حجز موعد </a>

                <?php }?>
            </nav><!-- BUTTONS & USER PROFILE AREA -->
<div class="flex items-center gap-4">

    <?php 
    $user_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'المستخدم'; 
    ?>
    <?php if ($user_role === 'guest'): ?>
        <!-- 1. حالة الزائر (غير مسجل دخول) -->
        <a href="login.php" class="hidden md:flex px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition text-sm">
            تسجيل الدخول
        </a>
        <a href="register.php">
            <button class="px-6 py-3 rounded-2xl bg-gradient-to-l from-sky-500 to-sky-600 text-white font-bold shadow-xl shadow-sky-200 hover:scale-105 transition transform duration-300 text-sm">
                سجل حساب 
            </button>
        </a>

    <?php elseif ($user_role === 'user'): ?>
        <div class="relative inline-block text-right group">
            <button class="flex items-center gap-3 px-4 py-2 rounded-2xl bg-white border border-slate-100 shadow-sm hover:bg-slate-50 transition cursor-pointer">
                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center font-black text-base">
                    <!-- عرض أول حرف من الاسم بشكل جمالي -->
                    <?= mb_substr($user_name, 0, 1, 'utf-8'); ?>
                </div>
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-slate-400 font-medium leading-none mb-1">مرحباً بك</p>
                    <p class="text-sm font-bold text-slate-700 leading-none"><?= $user_name; ?></p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 transition transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div class="absolute left-0 mt-2 w-52 origin-top-left bg-white border border-slate-100 rounded-2xl shadow-xl opacity-0 scale-95 pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto transition transform duration-200 z-50 overflow-hidden">
                <div class="p-1">
                    <a href="/tooth/user/dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-sky-600 rounded-xl transition">
                        <span>👤</span> الملف الشخصي
                    </a>
                    <a href="/tooth/user/all_bookings.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-sky-600 rounded-xl transition">
                        <span>📅</span> حجوزاتي
                    </a>
                    <hr class="border-slate-100 my-1">
                    <a href="/tooth/logout.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition">
                        <span>🚪</span> تسجيل الخروج
                    </a>
                </div>
            </div>
        </div>
    <?php elseif ($user_role === 'doctor'): ?>
        <div class="relative inline-block text-right group">
            <button class="flex items-center gap-3 px-4 py-2 rounded-2xl bg-slate-900 text-white shadow-xl shadow-slate-900/10 hover:bg-slate-800 transition cursor-pointer">
                <div class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center font-black text-lg">
                    👨‍⚕️
                </div>
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] text-sky-400 font-bold tracking-wider leading-none mb-1">لوحة الطبيب</p>
                    <p class="text-sm font-bold text-white leading-none">د. <?= $user_name; ?></p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 transition transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- قائمة التحكم المنسدلة للدكتور -->
            <div class="absolute left-0 mt-2 w-52 origin-top-left bg-white border border-slate-100 rounded-2xl shadow-xl opacity-0 scale-95 pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto transition transform duration-200 z-50 overflow-hidden">
                <div class="p-1">
                    <a href="/tooth/docotor/dashboard.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-sky-50 hover:text-sky-600 rounded-xl transition">
                        <span>📊</span> لوحة التحكم
                    </a>
                    <a href="/tooth/logout.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition">
                        <span>🚪</span> تسجيل الخروج
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

        </div>
    </div>
</header>