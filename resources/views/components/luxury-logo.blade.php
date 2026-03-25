<a href="{{ route('home') }}" class="luxury-logo inline-flex items-center gap-2 group">
    <div class="logo-icon relative">
        <svg viewBox="0 0 60 60" class="w-12 h-12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Outer Circle -->
            <circle cx="30" cy="30" r="28" stroke="url(#goldGradient)" stroke-width="2" class="opacity-80"/>
            <circle cx="30" cy="30" r="24" stroke="url(#goldGradient)" stroke-width="1" class="opacity-50"/>
            
            <!-- Inner Diamond Shape -->
            <path d="M30 10 L45 25 L30 45 L15 25 Z" fill="url(#goldGradient)" class="group-hover:animate-pulse"/>
            
            <!-- Shine Effect -->
            <path d="M30 15 L40 25 L30 40 L20 25 Z" fill="url(#goldShine)" class="animate-pulse"/>
            
            <!-- Sparkle Stars -->
            <g class="sparkles">
                <circle cx="12" cy="15" r="1.5" fill="#d4af37" class="sparkle-1">
                    <animate attributeName="opacity" values="0;1;0" dur="2s" repeatCount="indefinite"/>
                </circle>
                <circle cx="48" cy="45" r="1.5" fill="#d4af37" class="sparkle-2">
                    <animate attributeName="opacity" values="0;1;0" dur="2s" repeatCount="indefinite" begin="0.5s"/>
                </circle>
                <circle cx="45" cy="15" r="1" fill="#e8c547" class="sparkle-3">
                    <animate attributeName="opacity" values="0;1;0" dur="1.5s" repeatCount="indefinite" begin="1s"/>
                </circle>
                <circle cx="15" cy="45" r="1" fill="#e8c547" class="sparkle-4">
                    <animate attributeName="opacity" values="0;1;0" dur="1.5s" repeatCount="indefinite" begin="1.5s"/>
                </circle>
            </g>
            
            <!-- Gradients -->
            <defs>
                <linearGradient id="goldGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#d4af37"/>
                    <stop offset="50%" stop-color="#f4e4a6"/>
                    <stop offset="100%" stop-color="#d4af37"/>
                </linearGradient>
                <linearGradient id="goldShine" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#fff" stop-opacity="0.8"/>
                    <stop offset="100%" stop-color="#d4af37" stop-opacity="0"/>
                </linearGradient>
            </defs>
        </svg>
        
        <!-- Animated glow ring -->
        <div class="absolute inset-0 rounded-full bg-[#d4af37]/20 blur-xl animate-pulse group-hover:bg-[#d4af37]/40 transition-all duration-500"></div>
    </div>
    
    <div class="flex items-center gap-1">
        <span class="text-2xl font-serif font-bold text-[#d4af37] tracking-wider group-hover:text-[#e8c547] transition-colors duration-300">سوداقولد</span>
    </div>
</a>

<style>
.luxury-logo .sparkle-1,
.luxury-logo .sparkle-2,
.luxury-logo .sparkle-3,
.luxury-logo .sparkle-4 {
    animation: twinkle 2s ease-in-out infinite;
}

.luxury-logo .sparkle-2 {
    animation-delay: 0.5s;
}

.luxury-logo .sparkle-3 {
    animation-delay: 1s;
}

.luxury-logo .sparkle-4 {
    animation-delay: 1.5s;
}

@keyframes twinkle {
    0%, 100% { opacity: 0; transform: scale(0.5); }
    50% { opacity: 1; transform: scale(1.2); }
}

.luxury-logo:hover .logo-icon svg circle:first-child {
    stroke-width: 3;
    filter: drop-shadow(0 0 8px rgba(212, 175, 55, 0.8));
}

.luxury-logo:hover .logo-icon svg path {
    filter: drop-shadow(0 0 10px rgba(212, 175, 55, 0.9));
}
</style>
