---
name: anti-ai-slop
description: Enforce human-crafted, high-taste, production-grade UI/UX design. Prevents generic AI slop, tacky gradients, oversized shadows, and amateur gimmicks.
---

# Anti-AI-Slop UI/UX Skill

Enforce clean, intentional, human-crafted design principles. Eliminate generic AI tropes ("AI slop") and build crisp, production-grade interfaces.

## The Hallmarks of AI Slop (NEVER DO THESE)

1. **Gratuitous Gradients**: No `bg-gradient-to-r from-purple-600 to-indigo-600` on buttons, text, or card borders.
2. **Overblown Radii & Floating Cards**: No `rounded-3xl` with `shadow-2xl` on simple cards or tables.
3. **Emoji UI Decorators**: Never use emojis (🚀, ✨, 💡, 🔥, ⚡) as UI icons or list bullets.
4. **Glassmorphism Gimmicks**: Avoid `backdrop-blur-md bg-white/10` with neon borders unless specifically requested.
5. **Amateur Micro-Interactions**: Avoid `hover:scale-105` or bouncy spring animations on standard business UI buttons and cards.
6. **Low Information Density**: Do not drown data in excessive padding (`p-10` or `p-12`) when users need to read tables, forms, or dashboards efficiently.
7. **Fluffy Marketing Copy**: No corporate jargon ("Supercharge your potential", "Seamless synergy"). Use concise, human labels.

---

## The Human-Crafted Rules (ALWAYS DO THESE)

### 1. Typography & Hierarchy
- Use a disciplined type scale: `text-xs font-semibold uppercase tracking-wider` for table headers/badges, `text-sm font-medium` for body and form controls, `text-base` or `text-lg font-semibold` for card/section titles.
- High contrast and legibility: `text-slate-900 dark:text-slate-100` for primary text, `text-slate-500 dark:text-slate-400` for secondary metadata. Never use unreadable light gray on white.

### 2. Surface & Borders Over Shadows
- Delineate cards and sections with crisp, subtle borders: `border border-slate-200 dark:border-slate-800` rather than massive blurry shadows.
- Keep shadows tight: `shadow-xs` or `shadow-sm` at most.
- Keep border radii reasonable: `rounded-md` or `rounded-lg` for inputs and buttons; `rounded-xl` for cards/modals.

### 3. Restrained, Intentional Palette
- Primary: Single intentional accent color (e.g. `blue-600` or `emerald-600` depending on context).
- Neutrals: Cohesive slate/zinc/gray scale for backgrounds, borders, and text.
- Semantic: Standardized red for danger/delete (`rose-600`), green for success (`emerald-600`), amber for warnings (`amber-500`).

### 4. Crisp Icons & Data Density
- Use clean outline SVG icons (Heroicons/Lucide) sized consistently (`size-4` / `16px` for inline, `size-5` / `20px` for headers/buttons).
- Tables and lists should feel like real enterprise/school management tools: compact paddings (`py-3 px-4`), clear zebra or border dividers, clear sort/filter headers, and sticky headers where helpful.

### 5. Intentional States
- Focus rings: Always provide accessible `focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-primary-500`.
- Subtle hover transitions: `transition-colors duration-150 hover:bg-slate-50 dark:hover:bg-slate-800/50`.
