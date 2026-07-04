<template>
  <div class="font-body-md text-body-md antialiased sp-root" style="background-color: var(--sp-bg); color: var(--sp-text); min-height: 100vh;">

    <!-- Ambient background: dot grid + slow drifting mesh -->
    <div class="sp-ambient" aria-hidden="true">
      <svg class="sp-dotgrid" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <pattern id="sp-dots" x="0" y="0" width="28" height="28" patternUnits="userSpaceOnUse">
            <circle cx="1.4" cy="1.4" r="1.4" fill="rgba(50,205,50,0.14)" />
          </pattern>
          <radialGradient id="sp-fade" cx="50%" cy="30%" r="70%">
            <stop offset="0%" stop-color="#000" stop-opacity="0" />
            <stop offset="100%" stop-color="#000" stop-opacity="1" />
          </radialGradient>
          <mask id="sp-mask">
            <rect width="100%" height="100%" fill="url(#sp-fade)" />
          </mask>
        </defs>
        <rect width="100%" height="100%" fill="url(#sp-dots)" mask="url(#sp-mask)" />
      </svg>
    </div>

    <!-- Header / TopNavBar -->
    <header class="fixed top-0 w-full z-50 sp-header" :class="{ 'sp-header--scrolled': scrolled }">
      <div class="flex justify-between items-center px-margin-desktop py-4 max-w-container-max mx-auto">
        <div class="flex items-center gap-2.5">
          <span class="sp-logo-orb">
            <span class="sp-logo-orb-core"></span>
          </span>
          <a class="font-headline font-bold text-lg tracking-tight" href="#" style="color: var(--sp-text);">Social<span class="sp-gradient-text">Pulse</span></a>
        </div>
        <nav class="hidden md:flex items-center space-x-8">
          <a class="sp-nav-link sp-nav-link--active" href="#">Features</a>
          <a class="sp-nav-link" href="#">Community</a>
          <a class="sp-nav-link flex items-center gap-1.5" href="#">
            <span class="sp-live-dot"></span>
            Live
          </a>
          <a class="sp-nav-link" href="#">About</a>
        </nav>
        <div class="flex items-center gap-3">
          <!-- Dark mode toggle -->
          <button
            @click="toggleTheme"
            id="welcome-theme-toggle"
            class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[#32cd32]/10 hover:text-[#32cd32] sp-icon-btn"
            style="color: var(--sp-text-2); border: 1px solid var(--sp-border);"
          >
            <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
            </svg>
          </button>
          <a class="hover:text-[#32cd32] transition-colors text-sm font-semibold px-3 py-1.5" href="login" style="color: var(--sp-text-2);">Login</a>
          <a href="/register" ref="navRegisterBtn" @mousemove="magnetic" @mouseleave="resetMagnetic" class="sp-btn-primary text-sm px-5 py-2 sp-magnetic">
            Register
          </a>
        </div>
      </div>
    </header>

    <main class="pt-24 overflow-hidden" ref="mainContainer">

      <!-- Hero Section -->
      <section class="relative px-margin-mobile md:px-margin-desktop py-stack-xl flex flex-col items-center text-center max-w-container-max mx-auto min-h-[819px] justify-center">
        <div class="floating-orb -top-20 -left-20 orb-element"></div>
        <div class="floating-orb top-1/2 -right-40 orb-element"></div>

        <!-- Signature element: animated pulse / heartbeat line -->
        <svg class="sp-pulseline" viewBox="0 0 1200 200" preserveAspectRatio="none" aria-hidden="true">
          <defs>
            <linearGradient id="sp-line-fade" x1="0" y1="0" x2="1" y2="0">
              <stop offset="0%" stop-color="#32cd32" stop-opacity="0" />
              <stop offset="15%" stop-color="#32cd32" stop-opacity="0.9" />
              <stop offset="50%" stop-color="#4ce346" stop-opacity="1" />
              <stop offset="85%" stop-color="#32cd32" stop-opacity="0.9" />
              <stop offset="100%" stop-color="#32cd32" stop-opacity="0" />
            </linearGradient>
          </defs>
          <path
            class="sp-pulseline-path"
            d="M0,100 L260,100 L300,100 L330,40 L360,160 L390,20 L420,180 L450,100 L500,100 L900,100 L930,60 L960,140 L990,100 L1200,100"
            fill="none" stroke="url(#sp-line-fade)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
          />
        </svg>

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass-card mb-8 reveal">
          <span class="flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-primary opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
          </span>
          <span class="text-label-sm font-label-sm text-primary tracking-widest uppercase">New: Global Pulse v2.0</span>
        </div>

        <h1 class="font-headline-xl text-headline-xl md:text-[64px] mb-6 leading-[1.1] max-w-4xl tracking-tight text-on-surface reveal reveal-delay-1">
          Feel the <span class="gradient-text sp-shimmer">Beat</span> of the Global Community
        </h1>

        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mb-12 reveal reveal-delay-2">
          Join millions sharing real moments in real-time. Experience the social evolution built on trust, transparency, and true human connection.
        </p>

        <div class="flex flex-col sm:flex-row gap-6 reveal reveal-delay-3">
          <button
            ref="heroCta"
            @mousemove="magnetic"
            @mouseleave="resetMagnetic"
            class="btn-primary text-on-primary px-10 py-5 rounded-xl font-label-md text-label-md shadow-lg shadow-primary/20 hover:scale-105 transition-transform sp-magnetic sp-cta-ring"
          >
            Get Started
          </button>
          <button class="glass-card px-10 py-5 rounded-xl font-label-md text-label-md flex items-center gap-3 text-on-surface hover:bg-black/5 transition-colors group">
            <svg class="w-5 h-5 text-primary transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.6"/>
              <path d="M10 8.5L16 12L10 15.5V8.5Z" fill="currentColor"/>
            </svg>
            Watch Demo
          </button>
        </div>

        <!-- Social Proof / Avatars (inline SVG, no external images) -->
        <div class="mt-16 flex flex-col items-center gap-4 reveal reveal-delay-4">
          <div class="flex -space-x-3">
            <svg v-for="(a, i) in avatars" :key="i" class="w-12 h-12 rounded-full border-2 sp-avatar" style="border-color: var(--sp-bg);" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <linearGradient :id="`sp-av-grad-${i}`" x1="0" y1="0" x2="1" y2="1">
                  <stop offset="0%" :stop-color="a.c1" />
                  <stop offset="100%" :stop-color="a.c2" />
                </linearGradient>
              </defs>
              <circle cx="24" cy="24" r="24" :fill="`url(#sp-av-grad-${i})`" />
              <text x="24" y="30" text-anchor="middle" font-size="16" font-weight="700" fill="rgba(0,0,0,0.55)" font-family="sans-serif">{{ a.initials }}</text>
            </svg>
            <div class="w-12 h-12 rounded-full border-2 bg-primary text-on-primary flex items-center justify-center text-label-sm font-bold shadow-sm sp-avatar" style="border-color: var(--sp-bg);">
              +{{ statDisplay }}
            </div>
          </div>
          <p class="text-label-sm font-label-sm text-on-surface-variant">Trusted by over 2 million people worldwide</p>
        </div>
      </section>

      <!-- Live activity marquee -->
      <section class="py-6 border-y sp-marquee-section" style="border-color: var(--sp-border);">
        <div class="sp-marquee">
          <div class="sp-marquee-track">
            <span v-for="(item, i) in [...liveFeed, ...liveFeed]" :key="i" class="sp-marquee-item">
              <span class="sp-live-dot sp-live-dot--sm"></span>
              {{ item }}
            </span>
          </div>
        </div>
      </section>

      <!-- Features Bento Grid -->
      <section class="px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto">
        <div class="text-center mb-16 reveal">
          <h2 class="font-headline-lg text-headline-lg mb-4 text-on-surface">Redefining Social Interaction</h2>
          <div class="h-1.5 w-24 bg-primary mx-auto rounded-full sp-underline-grow"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
          <div
            v-for="(f, i) in features"
            :key="i"
            class="glass-card animated-card p-stack-lg rounded-3xl flex flex-col gap-6 group sp-tilt"
            @mousemove="tilt"
            @mouseleave="resetTilt"
          >
            <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors sp-icon-pop">
              <span class="material-symbols-outlined text-primary text-4xl">{{ f.icon }}</span>
            </div>
            <div>
              <h3 class="font-headline-md text-headline-md mb-3 text-on-surface">{{ f.title }}</h3>
              <p class="font-body-md text-body-md text-on-surface-variant">{{ f.desc }}</p>
            </div>
            <div class="mt-auto pt-6 border-t border-black/5 flex items-center gap-2 text-primary font-label-md group-hover:gap-3 transition-all cursor-pointer">
              {{ f.cta }} <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="relative px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto">
        <div class="relative glass-card rounded-[40px] overflow-hidden p-stack-xl md:p-24 text-center reveal">
          <!-- Inline SVG ambient pattern instead of external photo -->
          <svg class="absolute inset-0 z-0 w-full h-full sp-cta-pattern" preserveAspectRatio="xMidYMid slice" viewBox="0 0 800 400" aria-hidden="true">
            <defs>
              <radialGradient id="sp-cta-glow" cx="50%" cy="0%" r="80%">
                <stop offset="0%" stop-color="#32cd32" stop-opacity="0.25" />
                <stop offset="100%" stop-color="#32cd32" stop-opacity="0" />
              </radialGradient>
            </defs>
            <rect width="800" height="400" fill="url(#sp-cta-glow)" />
            <g stroke="rgba(50,205,50,0.18)" stroke-width="1">
              <circle cx="400" cy="180" r="90" />
              <circle cx="400" cy="180" r="150" />
              <circle cx="400" cy="180" r="210" class="sp-ring-pulse" />
            </g>
          </svg>
          <div class="relative z-10 flex flex-col items-center">
            <h2 class="font-headline-xl text-headline-xl mb-6 text-on-surface">Join the Pulse</h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant mb-12 max-w-xl">
              The future of social connection is here. Don't just watch the conversation, be the beat that drives it forward.
            </p>
            <div class="w-full max-w-md flex flex-col sm:flex-row gap-4">
              <input class="flex-grow bg-white border border-black/10 text-on-surface rounded-xl focus:ring-primary focus:border-primary px-6 py-4 placeholder:text-on-surface-variant/50" placeholder="Enter your email" type="email"/>
              <button
                ref="joinBtn"
                @mousemove="magnetic"
                @mouseleave="resetMagnetic"
                class="bg-primary text-on-primary px-8 py-4 rounded-xl font-label-md text-label-md whitespace-nowrap hover:shadow-lg hover:shadow-primary/30 transition-all sp-magnetic"
              >
                Register Now
              </button>
            </div>
            <p class="mt-6 text-label-sm font-label-sm text-on-surface-variant opacity-70">
              Join today and get a verified founder badge on your profile.
            </p>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer class="w-full py-stack-xl bg-surface-container-lowest border-t border-black/5">
      <div class="flex flex-col md:flex-row justify-between items-center px-margin-desktop space-y-stack-md md:space-y-0 max-w-container-max mx-auto">
        <div class="flex flex-col gap-2 items-center md:items-start">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">leak_add</span>
            <span class="font-headline-md text-headline-md font-bold text-on-surface tracking-tighter">Social Pulse</span>
          </div>
          <p class="font-body-sm text-body-sm text-on-surface-variant mt-2">© 2024 Social Pulse. All rights reserved.</p>
        </div>
        <nav class="flex flex-wrap justify-center gap-x-8 gap-y-4">
          <a class="text-on-surface-variant hover:text-primary transition-colors hover:underline font-body-sm text-body-sm" href="#">Privacy Policy</a>
          <a class="text-on-surface-variant hover:text-primary transition-colors hover:underline font-body-sm text-body-sm" href="#">Terms of Service</a>
          <a class="text-on-surface-variant hover:text-primary transition-colors hover:underline font-body-sm text-body-sm" href="#">Help Center</a>
          <a class="text-on-surface-variant hover:text-primary transition-colors hover:underline font-body-sm text-body-sm" href="#">Careers</a>
          <a class="text-on-surface-variant hover:text-primary transition-colors hover:underline font-body-sm text-body-sm" href="#">Contact</a>
        </nav>
        <div class="flex gap-4">
          <a class="w-10 h-10 rounded-full glass-card flex items-center justify-center hover:text-primary sp-icon-btn" href="#">
            <span class="material-symbols-outlined text-[20px]">public</span>
          </a>
          <a class="w-10 h-10 rounded-full glass-card flex items-center justify-center hover:text-primary sp-icon-btn" href="#">
            <span class="material-symbols-outlined text-[20px]">alternate_email</span>
          </a>
          <a class="w-10 h-10 rounded-full glass-card flex items-center justify-center hover:text-primary sp-icon-btn" href="#">
            <span class="material-symbols-outlined text-[20px]">share</span>
          </a>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

const mainContainer = ref(null);
const scrolled = ref(false);
let observer = null;

const isDark = ref(
  typeof window !== 'undefined'
    ? document.documentElement.classList.contains('dark')
    : false
);

const toggleTheme = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('sp-theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('sp-theme', 'light');
  }
};

// Content data
const avatars = [
  { initials: 'A', c1: '#32cd32', c2: '#1f8f1f' },
  { initials: 'M', c1: '#4ce346', c2: '#28a828' },
  { initials: 'J', c1: '#7bffb0', c2: '#32cd32' },
  { initials: 'S', c1: '#2ee6a6', c2: '#1f8f1f' },
];

const features = [
  {
    icon: 'bolt',
    title: 'Real-time Pulse',
    desc: 'Experience content as it happens. Our proprietary live engine delivers zero-latency updates for a truly immediate social experience.',
    cta: 'Learn more',
  },
  {
    icon: 'groups',
    title: 'Deep Connections',
    desc: 'Find your tribe in specialized community groups. We focus on quality interactions over quantity, fostering meaningful long-term bonds.',
    cta: 'Browse groups',
  },
  {
    icon: 'verified_user',
    title: 'Secure Sharing',
    desc: "Privacy isn't an option, it's our foundation. Your data is encrypted and stays yours. Control every aspect of your digital footprint.",
    cta: 'Our manifesto',
  },
];

const liveFeed = [
  'Amara just started a live pulse in Lagos',
  'Kenji joined the Design Systems group',
  'Priya shared a moment from Tokyo',
  'A new community "Indie Makers" just launched',
  'Liam hit 10K pulses this week',
  'Fatima went live from Cairo',
];

// Animated stat counter (2M+)
const statDisplay = ref('0');
const animateStat = () => {
  const target = 2;
  let current = 0;
  const steps = 40;
  const inc = target / steps;
  const timer = setInterval(() => {
    current += inc;
    if (current >= target) {
      current = target;
      clearInterval(timer);
    }
    statDisplay.value = `${current.toFixed(1)}M`;
  }, 30);
};

// Magnetic button effect
const magnetic = (e) => {
  const el = e.currentTarget;
  const rect = el.getBoundingClientRect();
  const x = e.clientX - rect.left - rect.width / 2;
  const y = e.clientY - rect.top - rect.height / 2;
  el.style.transform = `translate(${x * 0.18}px, ${y * 0.35}px)`;
};
const resetMagnetic = (e) => {
  e.currentTarget.style.transform = 'translate(0px, 0px)';
};

// Card tilt effect
const tilt = (e) => {
  const el = e.currentTarget;
  const rect = el.getBoundingClientRect();
  const x = (e.clientX - rect.left) / rect.width - 0.5;
  const y = (e.clientY - rect.top) / rect.height - 0.5;
  el.style.transform = `perspective(800px) rotateX(${-y * 6}deg) rotateY(${x * 6}deg) translateY(-4px)`;
};
const resetTilt = (e) => {
  e.currentTarget.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg) translateY(0px)';
};

const handleMouseMove = (e) => {
  const orbs = document.querySelectorAll('.orb-element');
  const x = (e.clientX / window.innerWidth) - 0.5;
  const y = (e.clientY / window.innerHeight) - 0.5;
  orbs.forEach((orb, index) => {
    const speed = (index + 1) * 40;
    orb.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
  });
};

const handleScroll = () => {
  scrolled.value = window.scrollY > 12;
};

onMounted(() => {
  window.addEventListener('mousemove', handleMouseMove);
  window.addEventListener('scroll', handleScroll, { passive: true });

  const observerOptions = { threshold: 0.15 };
  observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('sp-revealed');
        if (entry.target.classList.contains('sp-stat-trigger')) {
          animateStat();
        }
      }
    });
  }, observerOptions);

  document.querySelectorAll('.animated-card').forEach(card => {
    card.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-10');
    observer.observe(card);
  });

  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

  // trigger the stat counter once hero mounts
  animateStat();
});

onUnmounted(() => {
  window.removeEventListener('mousemove', handleMouseMove);
  window.removeEventListener('scroll', handleScroll);
  if (observer) observer.disconnect();
});
</script>

<style scoped>
:root {
  --sp-primary-glow: rgba(50, 205, 50, 0.25);
}

.sp-root {
  position: relative;
}

/* Ambient dot-grid background */
.sp-ambient {
  position: fixed;
  inset: 0;
  z-index: 0;
  pointer-events: none;
}
.sp-dotgrid {
  width: 100%;
  height: 100%;
  opacity: 0.6;
}

.glass-card {
  position: relative;
  z-index: 1;
  background-color: var(--sp-card);
  border: 1px solid var(--sp-border);
  backdrop-filter: blur(20px);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.glass-card:hover {
  border-color: rgba(50, 205, 50, 0.35);
  box-shadow: 0 20px 40px var(--sp-primary-glow);
}

.gradient-text {
  background: linear-gradient(135deg, #28a828 0%, #32cd32 50%, #4ce346 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.sp-shimmer {
  background-size: 200% auto;
  animation: sp-shimmer-move 4s ease-in-out infinite;
}
@keyframes sp-shimmer-move {
  0%, 100% { background-position: 0% center; }
  50% { background-position: 100% center; }
}

.sp-gradient-text {
  background: linear-gradient(135deg, #32cd32 0%, #4ce346 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.btn-primary {
  background: linear-gradient(135deg, #32cd32 0%, #28a828 100%);
  color: #000;
  transition: transform 0.15s ease, box-shadow 0.2s ease;
}
.sp-cta-ring {
  position: relative;
}
.sp-cta-ring::after {
  content: '';
  position: absolute;
  inset: -6px;
  border-radius: inherit;
  border: 1.5px solid rgba(50, 205, 50, 0.5);
  opacity: 0;
  transform: scale(0.9);
  transition: opacity 0.25s ease, transform 0.25s ease;
  pointer-events: none;
}
.sp-cta-ring:hover::after {
  opacity: 1;
  transform: scale(1);
}

.sp-magnetic {
  transition: transform 0.15s ease-out;
  will-change: transform;
}

/* Header */
.sp-header {
  transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
  border-bottom: 1px solid transparent;
}
.sp-header--scrolled {
  background-color: color-mix(in srgb, var(--sp-bg) 82%, transparent);
  backdrop-filter: blur(16px);
  border-bottom-color: var(--sp-border);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
}

.sp-nav-link {
  font-size: 0.875rem;
  color: var(--sp-text-2);
  position: relative;
  transition: color 0.2s ease;
}
.sp-nav-link::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -4px;
  width: 0;
  height: 2px;
  background: #32cd32;
  transition: width 0.25s ease;
}
.sp-nav-link:hover {
  color: #32cd32;
}
.sp-nav-link:hover::after {
  width: 100%;
}
.sp-nav-link--active {
  color: #32cd32;
  font-weight: 700;
}
.sp-nav-link--active::after {
  width: 100%;
}

.sp-icon-btn {
  transition: transform 0.2s ease, background-color 0.2s ease, color 0.2s ease;
}
.sp-icon-btn:hover {
  transform: translateY(-2px);
}

.sp-logo-orb {
  position: relative;
  width: 10px;
  height: 10px;
  display: inline-flex;
}
.sp-logo-orb-core {
  width: 10px;
  height: 10px;
  border-radius: 9999px;
  background: #32cd32;
  box-shadow: 0 0 12px rgba(50, 205, 50, 0.7);
  display: block;
  animation: sp-pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.sp-live-dot {
  width: 8px;
  height: 8px;
  border-radius: 9999px;
  background: #32cd32;
  display: inline-block;
  animation: sp-pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
.sp-live-dot--sm {
  width: 6px;
  height: 6px;
  margin-right: 8px;
}

@keyframes sp-pulse-ring {
  0%, 100% { opacity: 0.55; transform: scale(1); }
  50% { opacity: 1; transform: scale(1.15); }
}

.floating-orb {
  position: absolute;
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, rgba(50, 205, 50, 0.1) 0%, transparent 70%);
  border-radius: 50%;
  filter: blur(60px);
  z-index: -1;
  pointer-events: none;
  transition: transform 0.3s ease-out;
}

/* Signature: heartbeat / pulse line behind hero */
.sp-pulseline {
  position: absolute;
  top: 12%;
  left: 0;
  width: 100%;
  height: 200px;
  opacity: 0.55;
  pointer-events: none;
  z-index: 0;
}
.sp-pulseline-path {
  stroke-dasharray: 1400;
  stroke-dashoffset: 1400;
  animation: sp-draw-line 6s ease-in-out infinite;
}
@keyframes sp-draw-line {
  0% { stroke-dashoffset: 1400; }
  45% { stroke-dashoffset: 0; }
  100% { stroke-dashoffset: -1400; }
}

/* Scroll reveal */
.reveal {
  opacity: 0;
  transform: translateY(24px);
  transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1), transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}
.reveal.sp-revealed {
  opacity: 1;
  transform: translateY(0);
}
.reveal-delay-1 { transition-delay: 0.08s; }
.reveal-delay-2 { transition-delay: 0.16s; }
.reveal-delay-3 { transition-delay: 0.24s; }
.reveal-delay-4 { transition-delay: 0.32s; }

.sp-underline-grow {
  transform: scaleX(0);
  transform-origin: center;
  transition: transform 0.6s ease 0.2s;
}
.reveal.sp-revealed .sp-underline-grow,
.sp-revealed.sp-underline-grow {
  transform: scaleX(1);
}

/* Avatars */
.sp-avatar {
  transition: transform 0.25s ease;
}
.sp-avatar:hover {
  transform: translateY(-4px) scale(1.08);
  z-index: 2;
}

/* Card tilt */
.sp-tilt {
  transform-style: preserve-3d;
  transition: transform 0.15s ease-out, border-color 0.3s ease, box-shadow 0.3s ease;
}
.sp-icon-pop {
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.group:hover .sp-icon-pop {
  transform: scale(1.1) rotate(-4deg);
}

/* Marquee */
.sp-marquee-section {
  position: relative;
  overflow: hidden;
  background: linear-gradient(90deg, var(--sp-bg) 0%, transparent 10%, transparent 90%, var(--sp-bg) 100%);
}
.sp-marquee {
  overflow: hidden;
  width: 100%;
}
.sp-marquee-track {
  display: flex;
  gap: 3rem;
  width: max-content;
  animation: sp-marquee-scroll 32s linear infinite;
}
.sp-marquee-item {
  display: inline-flex;
  align-items: center;
  white-space: nowrap;
  font-size: 0.875rem;
  color: var(--sp-text-2);
}
@keyframes sp-marquee-scroll {
  from { transform: translateX(0); }
  to { transform: translateX(-50%); }
}

/* CTA pattern */
.sp-cta-pattern { }
.sp-ring-pulse {
  animation: sp-ring-expand 3.5s ease-out infinite;
  transform-origin: 400px 180px;
}
@keyframes sp-ring-expand {
  0% { opacity: 0.5; transform: scale(0.85); }
  100% { opacity: 0; transform: scale(1.25); }
}

/* Text color overrides using CSS vars */
.text-on-surface         { color: var(--sp-text); }
.text-on-surface-variant { color: var(--sp-text-2); }
.bg-surface-container-lowest { background-color: var(--sp-bg); }

/* Respect reduced motion */
@media (prefers-reduced-motion: reduce) {
  .sp-pulseline-path,
  .sp-marquee-track,
  .sp-logo-orb-core,
  .sp-live-dot,
  .sp-ring-pulse,
  .sp-shimmer {
    animation: none !important;
  }
  .reveal {
    transition: none !important;
    opacity: 1 !important;
    transform: none !important;
  }
}
</style>