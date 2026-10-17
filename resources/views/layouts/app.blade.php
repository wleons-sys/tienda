<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Paquixic-SCAC | Sistema de Control de Aportaciones Comunitarias')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    
    <!-- Styles principales -->
    @stack('styles')
    
    <!-- Estilos base -->
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{
            --agua:#0EA5E9;
            --agua-dark:#0369A1;
            --agua-pale:#E0F2FE;
            --caminos:#F59E0B;
            --caminos-dark:#B45309;
            --caminos-pale:#FEF3C7;
            --elec:#10B981;
            --elec-dark:#065F46;
            --elec-pale:#D1FAE5;
            --morado:#7C3AED;
            --morado-pale:#EDE9FE;
            --bg:#F8FAFC;
            --surface:#FFFFFF;
            --text:#1E293B;
            --text2:#475569;
            --text3:#94A3B8;
            --border:#E2E8F0;
        }
        body{
            font-family:'Nunito',sans-serif;
            background:var(--bg);
            color:var(--text);
            min-height:100vh;
            display:flex;
            flex-direction:column;
        }
        .main{
            flex:1;
            max-width:1000px;
            margin:0 auto;
            padding:2.5rem 1.5rem 4rem;
            width:100%;
        }
        
        @media(max-width:600px){
            .main{padding:2rem 1rem 3rem;}
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    @include('components.header')
    
    <!-- CONTENIDO PRINCIPAL -->
    <main class="main">
        @yield('content')
    </main>
    
    <!-- FOOTER -->
    @include('components.footer')
    
    <!-- MODALES DE SERVICIOS -->
    @include('partials.modales')
    
    <!-- SCRIPTS -->
    @stack('scripts')
</body>
</html>