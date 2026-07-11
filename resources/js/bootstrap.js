// import Echo from "laravel-echo";
// import Pusher from "pusher-js";

// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: "reverb",
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: import.meta.env.VITE_REVERB_HOST,
//     wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
//     wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
//     enabledTransports: ["ws", "wss"],
// });
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

// Only instantiate Echo if the Pusher key was available at build time.
// Vite bakes VITE_* env vars into the bundle — if the key is missing on the
// build server the value becomes undefined and Pusher throws synchronously,
// crashing Vue before it can mount (white screen).
const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;

if (pusherKey) {
    window.Echo = new Echo({
        broadcaster: "pusher",
        key: pusherKey,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
        forceTLS: true,
    });
} else {
    console.warn(
        "[bootstrap] VITE_PUSHER_APP_KEY is not set — real-time features " +
        "will be unavailable. Add this variable to your Render environment " +
        "and redeploy so Vite can bake it into the bundle."
    );
}

