<div class="luxury-logo-animated inline-flex items-center justify-center relative">
    <!-- Gold Bullion Particles -->
    <div class="absolute inset-0 pointer-events-none">
        <span class="gold-particle" style="top: 0; left: 50%; --tx: -15px; --ty: -15px;"></span>
        <span class="gold-particle" style="top: 20%; left: 0; --tx: -20px; --ty: 0px;"></span>
        <span class="gold-particle" style="top: 80%; left: 0; --tx: -20px; --ty: 0px;"></span>
        <span class="gold-particle" style="top: 100%; left: 50%; --tx: -15px; --ty: 15px;"></span>
        <span class="gold-particle" style="top: 80%; right: 0; --tx: 20px; --ty: 0px;"></span>
        <span class="gold-particle" style="top: 20%; right: 0; --tx: 20px; --ty: 0px;"></span>
    </div>
    
    <a href="{{ route('home') }}" class="relative z-10 inline-flex items-center gap-2 group">
        <div class="logo-icon relative">
            <svg viewBox="0 0 60 60" class="w-12 h-12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="30" cy="30" r="28" stroke="url(#goldGradient2)" stroke-width="2" class="opacity-80"/>
                <circle cx="30" cy="30" r="24" stroke="url(#goldGradient2)" stroke-width="1" class="opacity-50"/>
                <path d="M30 10 L45 25 L30 45 L15 25 Z" fill="url(#goldGradient2)" class="group-hover:animate-pulse"/>
                <path d="M30 15 L40 25 L30 40 L20 25 Z" fill="url(#goldShine2)" class="animate-pulse"/>
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
                <defs>
                    <linearGradient id="goldGradient2" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#d4af37"/>
                        <stop offset="50%" stop-color="#f4e4a6"/>
                        <stop offset="100%" stop-color="#d4af37"/>
                    </linearGradient>
                    <linearGradient id="goldShine2" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#fff" stop-opacity="0.8"/>
                        <stop offset="100%" stop-color="#d4af37" stop-opacity="0"/>
                    </linearGradient>
                </defs>
            </svg>
            <div class="absolute inset-0 rounded-full bg-[#d4af37]/20 blur-xl animate-pulse group-hover:bg-[#d4af37]/40 transition-all duration-500"></div>
        </div>
        
        <div class="flex items-center gap-1">
            <span class="text-2xl font-serif font-bold text-[#d4af37] tracking-wider group-hover:text-[#e8c547] transition-colors duration-300">سوداقولد</span>
        </div>
    </a>
</div>

<style>
.luxury-logo-animated:hover .gold-particle {
    animation: particleFloat 1s ease-out forwards;
}

.luxury-logo-animated .gold-particle:nth-child(1) { animation-delay: 0s; }
.luxury-logo-animated .gold-particle:nth-child(2) { animation-delay: 0.1s; }
.luxury-logo-animated .gold-particle:nth-child(3) { animation-delay: 0.2s; }
.luxury-logo-animated .gold-particle:nth-child(4) { animation-delay: 0.3s; }
.luxury-logo-animated .gold-particle:nth-child(5) { animation-delay: 0.15s; }
.luxury-logo-animated .gold-particle:nth-child(6) { animation-delay: 0.25s; }

@keyframes particleFloat {
    0% { opacity: 1; transform: translate(0, 0) scale(1); }
    100% { opacity: 0; transform: translate(var(--tx), var(--ty)) scale(0); }
}

.luxury-logo-animated .sparkle-1,
.luxury-logo-animated .sparkle-2,
.luxury-logo-animated .sparkle-3,
.luxury-logo-animated .sparkle-4 {
    animation: twinkle 2s ease-in-out infinite;
}

.luxury-logo-animated .sparkle-2 { animation-delay: 0.5s; }
.luxury-logo-animated .sparkle-3 { animation-delay: 1s; }
.luxury-logo-animated .sparkle-4 { animation-delay: 1.5s; }

@keyframes twinkle {
    0%, 100% { opacity: 0; transform: scale(0.5); }
    50% { opacity: 1; transform: scale(1.2); }
}

.luxury-logo-animated:hover .logo-icon svg circle:first-child {
    stroke-width: 3;
    filter: drop-shadow(0 0 8px rgba(212, 175, 55, 0.8));
}

.luxury-logo-animated:hover .logo-icon svg path {
    filter: drop-shadow(0 0 10px rgba(212, 175, 55, 0.9));
}
</style>
