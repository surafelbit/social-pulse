<template>
  <div class="font-body-md text-body-md antialiased" style="background-color: var(--sp-bg); color: var(--sp-text); min-height: 100vh;">
    <!-- Header / TopNavBar -->
    <header class="fixed top-0 w-full z-50 sp-header">
      <div class="flex justify-between items-center px-margin-desktop py-4 max-w-container-max mx-auto">
        <div class="flex items-center gap-2.5">
          <span class="w-2.5 h-2.5 rounded-full bg-[#32cd32] shadow-[0_0_12px_rgba(50,205,50,0.7)] pulse-glow"></span>
          <a class="font-headline font-bold text-lg tracking-tight" href="#" style="color: var(--sp-text);">Social<span class="sp-gradient-text">Pulse</span></a>
        </div>
        <nav class="hidden md:flex items-center space-x-8">
          <a class="text-[#32cd32] font-bold border-b-2 border-[#32cd32] pb-1 text-sm" href="#">Features</a>
          <a class="hover:text-[#32cd32] transition-colors text-sm" href="#" style="color: var(--sp-text-2);">Community</a>
          <a class="hover:text-[#32cd32] transition-colors text-sm flex items-center gap-1.5" href="#" style="color: var(--sp-text-2);">
            <span class="w-2 h-2 rounded-full bg-[#32cd32] pulse-glow"></span>
            Live
          </a>
          <a class="hover:text-[#32cd32] transition-colors text-sm" href="#" style="color: var(--sp-text-2);">About</a>
        </nav>
        <div class="flex items-center gap-3">
          <!-- Dark mode toggle -->
          <button
            @click="toggleTheme"
            id="welcome-theme-toggle"
            class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[#32cd32]/10 hover:text-[#32cd32]"
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
          <a href="/register" class="sp-btn-primary text-sm px-5 py-2">
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
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass-card mb-8">
          <span class="flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-primary opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
          </span>
          <span class="text-label-sm font-label-sm text-primary tracking-widest uppercase">New: Global Pulse v2.0</span>
        </div>
        <h1 class="font-headline-xl text-headline-xl md:text-[64px] mb-6 leading-[1.1] max-w-4xl tracking-tight text-on-surface">
          Feel the <span class="gradient-text">Beat</span> of the Global Community
        </h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mb-12">
          Join millions sharing real moments in real-time. Experience the social evolution built on trust, transparency, and true human connection.
        </p>
        <div class="flex flex-col sm:flex-row gap-6">
          <button class="btn-primary text-on-primary px-10 py-5 rounded-xl font-label-md text-label-md shadow-lg shadow-primary/20 hover:scale-105 transition-transform">
            Get Started
          </button>
          <button class="glass-card px-10 py-5 rounded-xl font-label-md text-label-md flex items-center gap-3 text-on-surface hover:bg-black/5 transition-colors">
            <span class="material-symbols-outlined text-primary">play_circle</span>
            Watch Demo
          </button>
        </div>
        
        <!-- Social Proof / Avatars -->
        <div class="mt-16 flex flex-col items-center gap-4">
          <div class="flex -space-x-3">
            <img class="w-12 h-12 rounded-full border-2 border-white object-cover shadow-sm" alt="Portrait 1" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD8c4YKt2Mu_SWwgjZt46vlV4jOTG02gGgYCYlmim9ypuh2_cy_pn_6HZ_3mAfVSLv1lgWBrl6SI9-_AF15uWtnSceerkN3TVGgvZ1XujmBgg7Wf4HYeSJFwAKqpz8WmpcKkCu-iVfZQOuvJWWFuNbhrJ09AWahgtr0vBeB7OoskA4rQRm17rntR7bIsACwTG9OM4CKzmcNb8QPEyD8xmGFMCCLpAYBOjZoYNFvvWEKU0OfGNYniI37PQ"/>
            <img class="w-12 h-12 rounded-full border-2 border-white object-cover shadow-sm" alt="Portrait 2" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAw2aTb5RdyLOWsyGcdy3TpSXO1AIsQxh-e1k3wqHLLxYA0YHR-ovOJE0bgnj2wUuLhB6Ug4y_KO-tzTwE6a15iuxdgpSGn5aqVCSE1xawf9XfeoSYQXEhxS6HrOfY6rcnouRYq6s9yG9H5UxWnKopcE1x2e45K1aQLofcKZ4973pT3VloTuLuFV2EZbEG2U-NWdKRs6lTkvpZk0upLpvvODXNeyUj3BNjNKBR4gtTaFEGm34ICNOuvOg"/>
            <img class="w-12 h-12 rounded-full border-2 border-white object-cover shadow-sm" alt="Portrait 3" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDLnZs0zIXPRJ_fOsnTQl2UkvJ2EJsP_BPMTliMFGEM8jdYFtbFeiwZFVlRa7Zpp87Q7avyH0Uf3R9wDuLjdfqvQqKaUdutzQe0TUfOdiDs_lerQnFU9UTXLTWIjWS-9t39iAwtneZaTkyhWt3XYsGGyhvuh6UEfDNBCJUkHGvSVgz1ydLoLbX4je30ZIuO9VJiWPVAufPEnJ7S-lZoJ-lVHgIbs9_6Gn14Q1D03VgNDLkfAPVm22yvCQ"/>
            <img class="w-12 h-12 rounded-full border-2 border-white object-cover shadow-sm" alt="Portrait 4" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAWrpGC8REJG6MS9IKg20V5ocTef6KmH83Kyc1oJstq7uxJcWklBq7U3k3Vogp1mkx35sL5wG9lqqv0O75UXYD1SE2lPcPeuLDt5tTwSJ4vF9jgkJARyrbvqq6XN8xLqxF5pLfSFLX6r-UzHR3yx92_Yr85SAxBgf96l35TKGuZnwdkzjQYxMlCgsBS1W20RAH2pZZl8yPb4TXMP3DQzgOL0SnlfAJTKc83vF0JMcbzvV05Mb0vN7W8Zg"/>
            <div class="w-12 h-12 rounded-full border-2 border-white bg-primary text-on-primary flex items-center justify-center text-label-sm font-bold shadow-sm">+2M</div>
          </div>
          <p class="text-label-sm font-label-sm text-on-surface-variant">Trusted by over 2 million people worldwide</p>
        </div>
      </section>

      <!-- Features Bento Grid -->
      <section class="px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto">
        <div class="text-center mb-16">
          <h2 class="font-headline-lg text-headline-lg mb-4 text-on-surface">Redefining Social Interaction</h2>
          <div class="h-1.5 w-24 bg-primary mx-auto rounded-full"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
          <!-- Card 1: Real-time Pulse -->
          <div class="glass-card animated-card p-stack-lg rounded-3xl flex flex-col gap-6 group">
            <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
              <span class="material-symbols-outlined text-primary text-4xl">ac_unit</span>
            </div>
            <div>
              <h3 class="font-headline-md text-headline-md mb-3 text-on-surface">Real-time Pulse</h3>
              <p class="font-body-md text-body-md text-on-surface-variant">
                Experience content as it happens. Our proprietary live engine delivers zero-latency updates for a truly immediate social experience.
              </p>
            </div>
            <div class="mt-auto pt-6 border-t border-black/5 flex items-center gap-2 text-primary font-label-md group-hover:gap-3 transition-all cursor-pointer">
              Learn more <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>
          </div>
          
          <!-- Card 2: Deep Connections -->
          <div class="glass-card animated-card p-stack-lg rounded-3xl flex flex-col gap-6 group">
            <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
              <span class="material-symbols-outlined text-primary text-4xl">groups</span>
            </div>
            <div>
              <h3 class="font-headline-md text-headline-md mb-3 text-on-surface">Deep Connections</h3>
              <p class="font-body-md text-body-md text-on-surface-variant">
                Find your tribe in specialized community groups. We focus on quality interactions over quantity, fostering meaningful long-term bonds.
              </p>
            </div>
            <div class="mt-auto pt-6 border-t border-black/5 flex items-center gap-2 text-primary font-label-md group-hover:gap-3 transition-all cursor-pointer">
              Browse groups <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>
          </div>

          <!-- Card 3: Secure Sharing -->
          <div class="glass-card animated-card p-stack-lg rounded-3xl flex flex-col gap-6 group">
            <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
              <span class="material-symbols-outlined text-primary text-4xl">verified_user</span>
            </div>
            <div>
              <h3 class="font-headline-md text-headline-md mb-3 text-on-surface">Secure Sharing</h3>
              <p class="font-body-md text-body-md text-on-surface-variant">
                Privacy isn't an option, it's our foundation. Your data is encrypted and stays yours. Control every aspect of your digital footprint.
              </p>
            </div>
            <div class="mt-auto pt-6 border-t border-black/5 flex items-center gap-2 text-primary font-label-md group-hover:gap-3 transition-all cursor-pointer">
              Our manifesto <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="relative px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto">
        <div class="relative glass-card rounded-[40px] overflow-hidden p-stack-xl md:p-24 text-center">
          <div class="absolute inset-0 z-0 opacity-10 scale-105 hover:scale-100 transition-transform duration-[10s]" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBwPCBL9oRZlqL689vqmEaYV4lupVX4UYXOWnGLX57cxIIp2_2uP5tUZRpBcgf6nbLAaHsf5alRYruh3KHrRSUTaXhdz-heEDg5VLcsVgYyYb1hzp7N_ekHO0jBcABoD4rlmfBsCfj4dWTJKdI38-291fjPhCv4Ev7wLsQmCDD3nNCttyY-L1V-VAmTBkXxdwpP4Hi8mm361lIA6LvBR1CBXs3dNhVLY35-cn-f5oDjhn2iZYQ63C-VSA'); background-size: cover; background-position: center; filter: grayscale(1) sepia(1) brightness(1.5);"></div>
          <div class="relative z-10 flex flex-col items-center">
            <h2 class="font-headline-xl text-headline-xl mb-6 text-on-surface">Join the Pulse</h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant mb-12 max-w-xl">
              The future of social connection is here. Don't just watch the conversation, be the beat that drives it forward.
            </p>
            <div class="w-full max-w-md flex flex-col sm:flex-row gap-4">
              <input class="flex-grow bg-white border border-black/10 text-on-surface rounded-xl focus:ring-primary focus:border-primary px-6 py-4 placeholder:text-on-surface-variant/50" placeholder="Enter your email" type="email"/>
              <button class="bg-primary text-on-primary px-8 py-4 rounded-xl font-label-md text-label-md whitespace-nowrap hover:shadow-lg hover:shadow-primary/30 transition-all">
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
          <a class="w-10 h-10 rounded-full glass-card flex items-center justify-center hover:text-primary" href="#">
            <span class="material-symbols-outlined text-[20px]">public</span>
          </a>
          <a class="w-10 h-10 rounded-full glass-card flex items-center justify-center hover:text-primary" href="#">
            <span class="material-symbols-outlined text-[20px]">alternate_email</span>
          </a>
          <a class="w-10 h-10 rounded-full glass-card flex items-center justify-center hover:text-primary" href="#">
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

const handleMouseMove = (e) => {
  const orbs = document.querySelectorAll('.orb-element');
  const x = (e.clientX / window.innerWidth) - 0.5;
  const y = (e.clientY / window.innerHeight) - 0.5;
  orbs.forEach((orb, index) => {
    const speed = (index + 1) * 40;
    orb.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
  });
};

onMounted(() => {
  window.addEventListener('mousemove', handleMouseMove);
  const observerOptions = { threshold: 0.1 };
  observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('opacity-100', 'translate-y-0');
        entry.target.classList.remove('opacity-0', 'translate-y-10');
      }
    });
  }, observerOptions);
  document.querySelectorAll('.animated-card').forEach(card => {
    card.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-10');
    observer.observe(card);
  });
});

onUnmounted(() => {
  window.removeEventListener('mousemove', handleMouseMove);
  if (observer) observer.disconnect();
});
</script>

<style scoped>
.glass-card {
  background-color: var(--sp-card);
  border: 1px solid var(--sp-border);
  backdrop-filter: blur(20px);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.glass-card:hover {
  border-color: rgba(50, 205, 50, 0.35);
  transform: translateY(-4px);
  box-shadow: 0 20px 40px var(--sp-primary-glow);
}
.gradient-text {
  background: linear-gradient(135deg, #28a828 0%, #32cd32 50%, #4ce346 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.btn-primary {
  background: linear-gradient(135deg, #32cd32 0%, #28a828 100%);
  color: #000;
  transition: all 0.2s ease;
}
.pulse-glow {
  animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
@keyframes pulse-ring {
  0%, 100% { opacity: 0.5; transform: scale(1); }
  50%       { opacity: 1;   transform: scale(1.05); }
}
.floating-orb {
  position: absolute;
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, rgba(50, 205, 50, 0.08) 0%, transparent 70%);
  border-radius: 50%;
  filter: blur(60px);
  z-index: -1;
  pointer-events: none;
}
/* Override text colors to use CSS vars */
.text-on-surface         { color: var(--sp-text); }
.text-on-surface-variant { color: var(--sp-text-2); }
.bg-surface-container-lowest { background-color: var(--sp-bg); }
</style>