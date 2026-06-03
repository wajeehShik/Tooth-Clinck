<?php
ob_start();
session_start();
require_once("../config/db.php");


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location:../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DentalCare Clinic</title>

    <!-- تضمين مكتبة Tailwind وخط Cairo -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- إعداد الخط الافتراضي والخلفية لـ Tailwind -->
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
                <a href="#" class="hover:text-sky-500 transition">الرئيسية</a>
                <a href="#services" class="hover:text-sky-500 transition">الخدمات</a>
                <a href="#about" class="hover:text-sky-500 transition">عن العيادة</a>
                <a href="#reviews" class="hover:text-sky-500 transition">آراء المرضى</a>
                <a href="#faq" class="hover:text-sky-500 transition">الأسئلة الشائعة</a>
                <a href="#contact" class="hover:text-sky-500 transition">تواصل معنا</a>
                <a href="booking.php" class="hover:text-sky-500 transition">حجز موعد </a>
            </nav>

            <!-- BUTTONS -->
            <div class="flex items-center gap-4">
                <button class="hidden md:flex px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition">
                    تسجيل الدخول
                </button>
                <a href="#booking">
                    <button class="px-6 py-3 rounded-2xl bg-gradient-to-l from-sky-500 to-sky-600 text-white font-bold shadow-xl shadow-sky-200 hover:scale-105 transition transform duration-300">
                        احجز الآن
                    </button>
                </a>
            </div>

        </div>
    </div>
</header>