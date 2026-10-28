<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

type FaqExample = {
    id: number;
    title: string;
    source: string;
};

type RewriteRecord = {
    id: number;
    source: string;
    status: string;
    rewrite: {
        rewritten: string;
        kept_facts: string[];
        missing_facts: string[];
        is_valid: boolean;
    } | null;
    created_at: string;
};

const props = defineProps<{
    examples: FaqExample[];
    latest: RewriteRecord | null;
    history: RewriteRecord[];
    provider: string;
}>();

const form = useForm({ source: props.latest?.source ?? '' });

function rewriteFaq() {
    form.post('/rewrite', { preserveScroll: true });
}
</script>

<template>
    <Head title="FAQ Rewrite Desk · Harbor Desk" />

    <div class="min-h-screen bg-[#f6f7f8] text-[#172b3a]">
        <header class="border-b border-[#e7e9e9] bg-white">
            <div
                class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8"
            >
                <a
                    href="/"
                    class="flex items-center gap-3"
                    aria-label="Harbor Desk home"
                >
                    <span
                        class="flex size-10 items-center justify-center rounded-xl bg-[#153a4b] text-white shadow-sm"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            class="size-5"
                            aria-hidden="true"
                        >
                            <path
                                d="M4 7.5A3.5 3.5 0 0 1 7.5 4h9A3.5 3.5 0 0 1 20 7.5v6a3.5 3.5 0 0 1-3.5 3.5H11l-5.5 3v-3.5A3.5 3.5 0 0 1 4 13V7.5Z"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M8 9h8M8 12.5h5"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />
                        </svg>
                    </span>
                    <span>
                        <span class="block text-sm font-semibold tracking-tight"
                            >Harbor Desk</span
                        >
                        <span class="block text-xs text-[#74818a]"
                            >Support knowledge tools</span
                        >
                    </span>
                </a>

                <div
                    class="flex items-center gap-2 rounded-full border border-[#e4e9e8] bg-[#fbfcfb] px-3 py-1.5 text-xs font-medium text-[#526570]"
                >
                    <span class="size-2 rounded-full bg-[#4c9a78]" />
                    FAQ rewrite desk
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-5 pt-10 pb-16 sm:px-8 sm:pt-14">
            <div
                class="mb-9 flex flex-col justify-between gap-6 sm:flex-row sm:items-end"
            >
                <div class="max-w-2xl">
                    <p
                        class="mb-3 text-xs font-semibold tracking-[0.18em] text-[#43766e] uppercase"
                    >
                        Support writing assistant
                    </p>
                    <h1
                        class="text-3xl font-semibold tracking-[-0.04em] text-[#172b3a] sm:text-[2.65rem]"
                    >
                        Make every answer easier to understand.
                    </h1>
                    <p
                        class="mt-3 max-w-xl text-[15px] leading-7 text-[#667680]"
                    >
                        Turn a long Harbor Desk FAQ into a clear, customer-ready
                        answer. Order IDs, numbers, and email addresses are
                        checked before it’s ready.
                    </p>
                </div>
                <div
                    class="flex shrink-0 items-center gap-2 rounded-xl border border-[#e4e9e8] bg-white px-3.5 py-2.5 text-xs text-[#65757e] shadow-sm"
                >
                    <span
                        class="size-2 rounded-full"
                        :class="
                            provider === 'Gemini'
                                ? 'bg-[#6486d8]'
                                : 'bg-[#d5a14c]'
                        "
                    />
                    <span
                        >Rewrite engine
                        <strong class="ml-1 font-semibold text-[#324b59]">{{
                            provider
                        }}</strong></span
                    >
                </div>
            </div>

            <div
                class="grid gap-5 lg:grid-cols-[minmax(0,1.08fr)_minmax(360px,0.92fr)]"
            >
                <section
                    class="rounded-2xl border border-[#e3e8e7] bg-white p-5 shadow-[0_5px_24px_rgba(24,48,58,0.035)] sm:p-7"
                    aria-labelledby="source-heading"
                >
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="flex size-6 items-center justify-center rounded-full bg-[#eaf2ef] text-[11px] font-bold text-[#427467]"
                                    >1</span
                                >
                                <h2
                                    id="source-heading"
                                    class="text-sm font-semibold text-[#243c4a]"
                                >
                                    Paste your FAQ
                                </h2>
                            </div>
                            <p class="mt-1 ml-8 text-xs text-[#7a888e]">
                                Start with an existing support answer.
                            </p>
                        </div>
                        <span
                            class="hidden rounded-md bg-[#f4f6f5] px-2 py-1 text-[10px] font-semibold tracking-wide text-[#77858a] uppercase sm:inline-flex"
                            >Source</span
                        >
                    </div>

                    <form @submit.prevent="rewriteFaq">
                        <label for="source" class="sr-only"
                            >FAQ source text</label
                        >
                        <textarea
                            id="source"
                            v-model="form.source"
                            rows="9"
                            maxlength="10000"
                            placeholder="Paste a Harbor Desk FAQ answer here…"
                            class="w-full resize-y rounded-xl border border-[#dfe5e4] bg-[#fcfdfc] px-4 py-3.5 text-sm leading-6 text-[#2d414c] transition outline-none placeholder:text-[#a0aaad] focus:border-[#7ea99d] focus:bg-white focus:ring-4 focus:ring-[#60947f]/10"
                            :aria-invalid="Boolean(form.errors.source)"
                            aria-describedby="source-hint source-error"
                        />
                        <div
                            class="mt-2 flex items-start justify-between gap-4"
                        >
                            <p
                                id="source-hint"
                                class="text-xs leading-5 text-[#879398]"
                            >
                                We’ll protect every order ID, number, and email
                                address we find.
                            </p>
                            <span
                                class="shrink-0 text-xs text-[#98a3a6] tabular-nums"
                                >{{ form.source.length }}/10,000</span
                            >
                        </div>
                        <p
                            v-if="form.errors.source"
                            id="source-error"
                            class="mt-2 text-sm text-[#b0443c]"
                        >
                            {{ form.errors.source }}
                        </p>

                        <button
                            type="submit"
                            :disabled="
                                form.processing ||
                                form.source.trim().length < 10
                            "
                            class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-[#174356] px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#103747] focus:ring-4 focus:ring-[#174356]/20 focus:outline-none disabled:cursor-not-allowed disabled:opacity-45 sm:w-auto sm:min-w-44"
                        >
                            <svg
                                v-if="form.processing"
                                class="size-4 animate-spin"
                                viewBox="0 0 24 24"
                                fill="none"
                                aria-hidden="true"
                            >
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                    stroke="currentColor"
                                    stroke-opacity=".25"
                                    stroke-width="3"
                                />
                                <path
                                    d="M21 12a9 9 0 0 0-9-9"
                                    stroke="currentColor"
                                    stroke-width="3"
                                    stroke-linecap="round"
                                />
                            </svg>
                            <svg
                                v-else
                                viewBox="0 0 20 20"
                                fill="none"
                                class="size-4"
                                aria-hidden="true"
                            >
                                <path
                                    d="M10 2.5v4M10 13.5v4M2.5 10h4M13.5 10h4M4.7 4.7l2.8 2.8m5 5 2.8 2.8m0-10.6-2.8 2.8m-5 5-2.8 2.8"
                                    stroke="currentColor"
                                    stroke-width="1.6"
                                    stroke-linecap="round"
                                />
                            </svg>
                            {{ form.processing ? 'Rewriting…' : 'Rewrite FAQ' }}
                        </button>
                    </form>

                    <div class="mt-7 border-t border-[#edf0ef] pt-5">
                        <p
                            class="mb-3 text-[11px] font-semibold tracking-[0.12em] text-[#7d8a8f] uppercase"
                        >
                            Try a sample FAQ
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="example in examples"
                                :key="example.id"
                                type="button"
                                @click="form.source = example.source"
                                class="rounded-full border border-[#e3e9e7] bg-white px-3 py-1.5 text-xs font-medium text-[#526872] transition hover:border-[#a9c3b9] hover:bg-[#f5f9f6] hover:text-[#2d6455] focus:ring-2 focus:ring-[#60947f]/20 focus:outline-none"
                            >
                                {{ example.title }}
                            </button>
                        </div>
                    </div>
                </section>

                <section
                    class="flex flex-col rounded-2xl border border-[#e3e8e7] bg-white p-5 shadow-[0_5px_24px_rgba(24,48,58,0.035)] sm:p-7"
                    aria-labelledby="rewrite-heading"
                >
                    <div class="mb-5 flex items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="flex size-6 items-center justify-center rounded-full bg-[#eaf2ef] text-[11px] font-bold text-[#427467]"
                                    >2</span
                                >
                                <h2
                                    id="rewrite-heading"
                                    class="text-sm font-semibold text-[#243c4a]"
                                >
                                    Plain-language answer
                                </h2>
                            </div>
                            <p class="mt-1 ml-8 text-xs text-[#7a888e]">
                                Ready to review and share.
                            </p>
                        </div>
                        <span
                            v-if="latest?.rewrite"
                            class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-semibold tracking-wide uppercase"
                            :class="
                                latest.rewrite.is_valid
                                    ? 'bg-[#eaf5ef] text-[#34775e]'
                                    : 'bg-[#fff0e8] text-[#a85136]'
                            "
                            ><span
                                class="size-1.5 rounded-full"
                                :class="
                                    latest.rewrite.is_valid
                                        ? 'bg-[#4d9b77]'
                                        : 'bg-[#cc6c4d]'
                                "
                            />{{
                                latest.rewrite.is_valid
                                    ? 'Facts verified'
                                    : 'Review needed'
                            }}</span
                        >
                    </div>

                    <template v-if="latest?.rewrite">
                        <div
                            class="min-h-36 rounded-xl bg-[#f7f9f8] p-4 sm:p-5"
                        >
                            <p
                                class="text-[15px] leading-7 whitespace-pre-wrap text-[#304954]"
                            >
                                {{ latest.rewrite.rewritten }}
                            </p>
                        </div>

                        <div
                            v-if="!latest.rewrite.is_valid"
                            role="alert"
                            class="mt-4 flex gap-3 rounded-lg border border-[#f0d3c5] bg-[#fff8f4] px-3.5 py-3 text-sm text-[#984a32]"
                        >
                            <svg
                                viewBox="0 0 20 20"
                                fill="none"
                                class="mt-0.5 size-4 shrink-0"
                                aria-hidden="true"
                            >
                                <path
                                    d="M10 6.3v4.2m0 3.2h.01M8.5 2.8 1.9 14.2A1.8 1.8 0 0 0 3.4 17h13.2a1.8 1.8 0 0 0 1.5-2.8L11.5 2.8a1.7 1.7 0 0 0-3 0Z"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            <span
                                ><strong class="font-semibold"
                                    >A protected fact is missing.</strong
                                >
                                Check the answer against the source before using
                                it.</span
                            >
                        </div>

                        <div class="mt-5">
                            <div
                                class="mb-2.5 flex items-center justify-between"
                            >
                                <h3
                                    class="text-[11px] font-semibold tracking-[0.12em] text-[#7d8a8f] uppercase"
                                >
                                    Protected facts
                                </h3>
                                <span class="text-[11px] text-[#8a9699]"
                                    >{{
                                        latest.rewrite.kept_facts.length
                                    }}
                                    found</span
                                >
                            </div>
                            <div
                                v-if="latest.rewrite.kept_facts.length"
                                class="flex flex-wrap gap-2"
                            >
                                <span
                                    v-for="fact in latest.rewrite.kept_facts"
                                    :key="fact"
                                    class="inline-flex items-center gap-1.5 rounded-md border px-2.5 py-1.5 font-mono text-xs"
                                    :class="
                                        latest.rewrite.missing_facts.includes(
                                            fact,
                                        )
                                            ? 'border-[#f0d3c5] bg-[#fff8f4] text-[#a85136]'
                                            : 'border-[#dceae3] bg-[#f3f8f5] text-[#3d705d]'
                                    "
                                >
                                    <svg
                                        v-if="
                                            !latest.rewrite.missing_facts.includes(
                                                fact,
                                            )
                                        "
                                        viewBox="0 0 16 16"
                                        fill="none"
                                        class="size-3"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="m3.5 8.2 2.7 2.6 6.3-6"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                    <span v-else aria-hidden="true">!</span>
                                    {{ fact }}
                                </span>
                            </div>
                            <p v-else class="text-xs text-[#8a9699]">
                                No numbers, order IDs, or email addresses found
                                in the source.
                            </p>
                        </div>
                    </template>

                    <div
                        v-else
                        class="flex min-h-[280px] flex-1 flex-col items-center justify-center rounded-xl border border-dashed border-[#dce4e1] bg-[#fafcfb] px-6 text-center"
                    >
                        <span
                            class="mb-4 flex size-12 items-center justify-center rounded-2xl bg-[#edf3f0] text-[#588276]"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                class="size-6"
                                aria-hidden="true"
                            >
                                <path
                                    d="M7 4.5h10A2.5 2.5 0 0 1 19.5 7v10a2.5 2.5 0 0 1-2.5 2.5H7A2.5 2.5 0 0 1 4.5 17V7A2.5 2.5 0 0 1 7 4.5Z"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                />
                                <path
                                    d="M8 9h8M8 12h8M8 15h5"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </span>
                        <p class="text-sm font-semibold text-[#435a64]">
                            Your rewrite will appear here
                        </p>
                        <p
                            class="mt-1.5 max-w-xs text-xs leading-5 text-[#839096]"
                        >
                            Paste an answer or choose one of the sample FAQs to
                            see the desk in action.
                        </p>
                    </div>

                    <div
                        v-if="history.length"
                        class="mt-6 border-t border-[#edf0ef] pt-5"
                    >
                        <p
                            class="mb-2.5 text-[11px] font-semibold tracking-[0.12em] text-[#7d8a8f] uppercase"
                        >
                            Recent rewrites
                        </p>
                        <div class="space-y-2">
                            <div
                                v-for="item in history.slice(0, 3)"
                                :key="item.id"
                                class="flex items-center justify-between gap-3 rounded-lg bg-[#f8faf9] px-3 py-2.5"
                            >
                                <p class="line-clamp-1 text-xs text-[#64757d]">
                                    {{ item.source }}
                                </p>
                                <span
                                    class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold"
                                    :class="
                                        item.status === 'invalid'
                                            ? 'bg-[#fff0e8] text-[#a85136]'
                                            : 'bg-[#eaf5ef] text-[#34775e]'
                                    "
                                    >{{
                                        item.status === 'invalid'
                                            ? 'Review'
                                            : 'Verified'
                                    }}</span
                                >
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <footer
                class="mt-7 flex flex-col gap-2 border-t border-[#e4e9e8] pt-5 text-xs leading-5 text-[#869297] sm:flex-row sm:items-center sm:justify-between"
            >
                <p>
                    Protected facts are checked automatically. Always review
                    support copy before publishing.
                </p>
                <p class="font-medium text-[#718188]">
                    Harbor Desk · Knowledge operations
                </p>
            </footer>
        </main>
    </div>
</template>
