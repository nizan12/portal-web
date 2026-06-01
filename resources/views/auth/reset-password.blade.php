<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Atur Ulang Kata Sandi - {{ config('app.name', 'POLTREE') }}</title>
    <meta name="description" content="Atur ulang kata sandi akun POLTREE Anda.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-slate-100 font-sans text-slate-900 antialiased">
    <main class="relative isolate flex min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('campus.png') }}');"
            aria-hidden="true"></div>
        <div class="absolute inset-0 bg-[linear-gradient(115deg,rgba(255,239,221,0.9)_0%,rgba(255,255,255,0.42)_44%,rgba(191,219,254,0.84)_100%)]"
            aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-24 bottom-[-7rem] h-[22rem] w-[28rem] rounded-[60%_40%_55%_45%/50%_60%_40%_50%] bg-white/55 blur-[2px]"
            aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-72 rounded-[45%_55%_40%_60%/55%_45%_60%_40%] bg-white/40"
            aria-hidden="true"></div>

        <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-6xl items-center px-6 py-10 lg:px-10">
            <div class="grid w-full gap-10 justify-items-center lg:justify-items-stretch lg:grid-cols-[minmax(0,1fr)_360px] lg:items-center xl:grid-cols-[minmax(0,1fr)_380px]">
                <section class="max-w-xl mx-auto lg:mx-0 text-center lg:text-left animate-fade-up [--animation-delay:0.1s]">
                    <div
                        class="mb-5 inline-flex items-center gap-3 rounded-full border border-white/70 bg-white/55 px-4 py-2 text-sm font-semibold text-slate-700 shadow-[0_10px_30px_rgba(15,23,42,0.08)] backdrop-blur-md">
                        <span class="h-2.5 w-2.5 rounded-full bg-orange-500"></span>
                        Kata Sandi Baru
                    </div>

                    <h1 class="max-w-lg text-4xl font-extrabold leading-tight tracking-tight text-[#091057] sm:text-5xl">
                        Atur Ulang Sandi
                    </h1>

                    <p class="mt-4 max-w-md text-lg leading-8 text-slate-700 sm:text-xl">
                        Silakan buat kata sandi baru yang aman dan mudah Anda ingat.
                    </p>
                </section>

                <section
                    class="w-full max-w-sm mx-auto lg:mx-0 animate-fade-in rounded-[28px] border border-white/75 bg-white/28 p-5 shadow-[0_20px_60px_rgba(15,23,42,0.14)] backdrop-blur-xl [--animation-delay:0.25s] sm:p-6"
                    aria-label="Form Reset Password">
                    <div class="mb-5 text-center lg:text-left">
                        <h2 class="text-xl font-bold text-[#091057]">Kata Sandi Baru</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Buat kata sandi baru untuk akun Anda.</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 rounded-2xl border border-red-200/80 bg-red-50/85 px-4 py-3 text-sm leading-6 text-red-700 shadow-sm"
                            role="alert">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update.post') }}" class="space-y-3" novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="space-y-1.5">
                            <label for="password" class="sr-only">Password Baru</label>
                            <div class="group relative">
                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-orange-500">
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <input id="password" type="password" name="password" placeholder="Password Baru" required autofocus
                                    class="h-12 w-full rounded-2xl border border-white/80 bg-white/85 pl-12 pr-4 text-sm font-medium text-slate-700 shadow-[inset_0_1px_0_rgba(255,255,255,0.7)] transition duration-200 placeholder:text-slate-400 focus:border-[#091057] focus:bg-white focus:ring-4 focus:ring-[#091057]/10">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="password_confirmation" class="sr-only">Konfirmasi Password</label>
                            <div class="group relative">
                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-orange-500">
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi Password Baru" required
                                    class="h-12 w-full rounded-2xl border border-white/80 bg-white/85 pl-12 pr-4 text-sm font-medium text-slate-700 shadow-[inset_0_1px_0_rgba(255,255,255,0.7)] transition duration-200 placeholder:text-slate-400 focus:border-[#091057] focus:bg-white focus:ring-4 focus:ring-[#091057]/10">
                            </div>
                        </div>

                        <button type="submit"
                            class="mt-2 inline-flex h-12 w-full items-center justify-center rounded-2xl bg-[#091057] text-sm font-semibold tracking-[0.03em] text-white shadow-[0_14px_30px_rgba(9,16,87,0.24)] transition duration-200 hover:bg-[#0d1a7a] hover:shadow-[0_18px_34px_rgba(9,16,87,0.28)] active:scale-[0.99]">
                            Simpan Kata Sandi Baru
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </main>
</body>

</html>
