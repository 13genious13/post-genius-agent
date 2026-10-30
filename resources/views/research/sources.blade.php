<x-app-layout>




    <div class="space-y-7">




        <div>




            <a
                href="{{ route('research.show', $researchRun) }}"
                class="text-sm font-semibold text-indigo-600"
            >
                ← Research Report
            </a>




            <h1 class="mt-4 text-3xl font-bold">
                Research Sources
            </h1>




            <p class="mt-2 text-sm text-slate-500">
                Evidence collected during Research Run v{{ $researchRun->version }}.
            </p>




        </div>




        <div class="space-y-5">




            @forelse($researchRun->sources as $source)




                <article
                    id="source-{{ $source->id }}"
                    class="scroll-mt-6 rounded-[26px] border border-slate-200 bg-white p-6"
                >




                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">




                        <div class="min-w-0">




                            <p class="text-xs font-bold uppercase text-indigo-600">
                                Source {{ $source->id }}
                                ·
                                {{ $source->domain }}
                            </p>




                            <h2 class="mt-2 text-lg font-bold text-slate-900">
                                {{ $source->title ?: $source->url }}
                            </h2>




                            <p class="mt-2 break-all text-xs text-slate-400">
                                {{ $source->url }}
                            </p>




                        </div>




                        @if($source->search_score)




                            <span class="shrink-0 rounded-xl bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-600">
Search Score:
{{ number_format((float) $source->search_score, 3) }}
</span>




                        @endif




                    </div>




                    @if($source->researchQuery)




                        <div class="mt-4 rounded-xl bg-indigo-50 p-3">




                            <p class="text-xs text-indigo-700">
                                Query:
                                {{ $source->researchQuery->query }}
                            </p>




                        </div>




                    @endif




                    <p class="mt-5 whitespace-pre-line text-sm leading-7 text-slate-600">
                        {{ \Illuminate\Support\Str::limit(
                        $source->content ?: $source->snippet,
                        2500
                        ) }}
                    </p>




                    <div class="mt-5">




                        <a
                            href="{{ $source->url }}"
                            target="_blank"
                            rel="noopener noreferrer nofollow"
                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-800"
                        >
                            Open Original Source ↗
                        </a>




                    </div>




                </article>




            @empty




                <div class="rounded-[28px] border border-dashed border-slate-300 bg-white py-16 text-center">
                    No sources were collected.
                </div>




            @endforelse




        </div>




    </div>




</x-app-layout>
