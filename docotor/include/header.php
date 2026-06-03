<?php 
ob_start();
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location:../index.php");
    exit();
}


$clinic_stmt = $pdo->query("SELECT name, logo FROM clinic LIMIT 1");
    $clinic_settings = $clinic_stmt->fetch(PDO::FETCH_ASSOC);


    
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم | <?php echo $clinic_settings['name']?></title>
    
<link href="../output.css" rel="stylesheet">
</head>
<body class="bg-[#F8FAFC] text-slate-800 min-h-screen flex overflow-x-hidden font-['Cairo'] selection:bg-sky-500 selection:text-white">
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden lg:hidden opacity-0 transition-opacity duration-300"></div>

    <aside id="sidebar" class="fixed inset-y-0 right-0 z-50 w-72 bg-white border-l border-slate-100 flex flex-col justify-between shadow-xl lg:shadow-sm translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        
        <div>
            <div class="flex items-center justify-between p-6 border-b border-slate-50">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tl from-[#0284C7] to-[#0EA5E9] flex items-center justify-center text-white text-xl shadow-md shadow-sky-100">
                        🦷
                    </div>
                    <div>
                        <h1 class="text-lg font-black text-slate-900 leading-none">DentalCare</h1>
                        <span class="text-xs text-slate-400 font-medium">لوحة الإشراف والتحكم</span>
                    </div>
                </div>
                <button id="closeSidebar" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-slate-100 transition-colors">
                    ✕
                </button>
            </div>

            <nav class="p-4 space-y-1.5 h-[calc(100vh-220px)] overflow-y-auto [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:bg-slate-200 [&::-webkit-scrollbar-thumb]:rounded-full">
                
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3 mb-2">الرئيسية</p>

                <a href="dashboard.php" class="flex items-center justify-between px-4 py-3.5 bg-sky-50 text-sky-600 font-bold rounded-2xl group transition-all duration-200">
                    <div class="flex items-center gap-3">
                        <span class="text-xl group-hover:scale-110 transition-transform duration-200">📊</span>
                        <span class="text-sm">الإحصائيات العامة</span>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                </a>

                <a href="dates.php" class="flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-semibold rounded-2xl group transition-all duration-200">
                    <span class="text-xl group-hover:scale-110 transition-transform duration-200">📅</span>
                    <span class="text-sm">الحجوزات والمواعيد</span>
                </a>

                <a href="users.php" class="flex items-center justify-between px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-semibold rounded-2xl group transition-all duration-200">
                    <div class="flex items-center gap-3">
                        <span class="text-xl group-hover:scale-110 transition-transform duration-200">👥</span>
                        <span class="text-sm">إدارة المرضى</span>
                    </div>
                </a>
                <a href="services.php" class="flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-semibold rounded-2xl group transition-all duration-200">
                    <span class="text-xl group-hover:scale-110 transition-transform duration-200">💼</span>
                    <span class="text-sm">الخدمات والأسعار</span>
                </a>
                <a href="session.php" class="flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-semibold rounded-2xl group transition-all duration-200">
                    <span class="text-xl group-hover:scale-110 transition-transform duration-200">🩺</span>
                    <span class="text-sm">الجلسات </span>
                </a>
                <a href="clinic.php" class="flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-semibold rounded-2xl group transition-all duration-200">
                    <span class="text-xl group-hover:scale-110 transition-transform duration-200">⚙️</span>
                    <span class="text-sm">إعدادات العيادة</span>
                </a>

                <a href="profile.php" class="flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-semibold rounded-2xl group transition-all duration-200">
                    <span class="text-xl group-hover:scale-110 transition-transform duration-200">⚙️</span>
                    <span class="text-sm"> حساب الشخصي</span>
                </a>
                <a href="faqs.php" class="flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-semibold rounded-2xl group transition-all duration-200">
                    <span class="text-xl group-hover:scale-110 transition-transform duration-200">⚙️</span>
                    <span class="text-sm">أسئلة الشائعة </span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-3 p-2 mb-2">
                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 font-bold flex items-center justify-center border border-sky-200 shrink-0">
                     د.أ
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 truncate">د. أحمد مصطفى</p>
                    <p class="text-xs text-slate-400 truncate">مدير النظام</p>
                </div>
            </div>
            
            <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 text-rose-600 hover:bg-rose-50 font-bold rounded-2xl group transition-all duration-200">
                <span class="text-xl group-hover:translate-x-1 transition-transform duration-300">🚪</span>
                <span class="text-sm">تسجيل الخروج</span>
            </a>
        </div>

    </aside>

    <div class="pr-0 lg:pr-72 w-full min-h-screen flex flex-col transition-all duration-300">
        
        <header class="h-20 bg-white border-b border-slate-100 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <button id="openSidebar" class="lg:hidden w-11 h-11 flex items-center justify-center rounded-xl bg-slate-50 border border-slate-200/60 text-xl hover:bg-slate-100 transition-colors">
                    ☰
                </button>
                <div>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900">لوحة الإحصائيات العامة</h2>
                    <p class="text-[11px] sm:text-xs text-slate-400 hidden sm:block">مرحباً بك مجدداً، إليك تقرير العيادة اليوم</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="notification.php" class="w-11 h-11 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200/60 flex items-center justify-center text-xl relative transition-colors">
                    🔔
                    <span class="absolute top-2.5 left-2.5 w-2.5 h-2.5 rounded-full bg-rose-500 border-2 border-white"></span>
</a>
             
            </div>
        </header>