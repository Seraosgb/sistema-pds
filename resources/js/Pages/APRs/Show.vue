<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    apr: Object,
});

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('pt-BR', options);
};
</script>

<template>
    <Head :title="'Detalhes da APR ' + apr.numero_apr" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalhes da APR: {{ apr.numero_apr }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 space-y-8">

                        <!-- Botões de Ação no Topo -->
                        <div class="flex justify-between items-center pb-4 border-b">
                            <Link :href="route('aprs.index')" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                &larr; Voltar para a Lista
                            </Link>
                            <div class="space-x-2">
                                <a :href="route('aprs.pdf', apr.id)" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Gerar PDF
                                </a>
                                <Link :href="route('aprs.edit', apr.id)" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Editar
                                </Link>
                            </div>
                        </div>

                        <!-- Seção 1: Dados Gerais -->
                        <section>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Dados Gerais</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                                <div><strong class="text-gray-600">Nº da APR:</strong> {{ apr.numero_apr }}</div>
                                <div><strong class="text-gray-600">Nome da Unidade:</strong> {{ apr.unidade_nome }}</div>
                                <div><strong class="text-gray-600">Responsável:</strong> {{ apr.responsavel_unidade }}</div>
                                <div><strong class="text-gray-600">Local:</strong> {{ apr.local }}</div>
                                <div><strong class="text-gray-600">Início:</strong> {{ formatDate(apr.inicio_at) }}</div>
                                <div><strong class="text-gray-600">Término:</strong> {{ formatDate(apr.termino_at) }}</div>
                                <div class="col-span-2"><strong class="text-gray-600">Atividade:</strong> {{ apr.atividade_descricao }}</div>
                            </div>
                        </section>

                        <!-- Seção 2: Itens de Risco -->
                        <section>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Análise de Risco</h3>
                            <div class="space-y-4">
                                <div v-for="(item, index) in apr.items" :key="item.id" class="p-4 border rounded-md bg-gray-50">
                                    <h4 class="font-bold text-md text-gray-800">Item {{ index + 1 }}: {{ item.tarefa }}</h4>
                                    <div class="mt-2 pl-4 border-l-2 border-gray-300 space-y-2 text-sm">
                                        <p><strong class="text-red-600">Risco:</strong> {{ item.risco }}</p>
                                        <p><strong class="text-green-600">Recomendação:</strong> {{ item.recomendacao }}</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Seção 3: Participantes -->
                        <section>
                             <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Participantes</h3>
                             <div class="relative overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">RE</th>
                                            <th scope="col" class="px-6 py-3">Nome</th>
                                            <th scope="col" class="px-6 py-3">Função</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="participant in apr.participants" :key="participant.id" class="bg-white border-b">
                                            <td class="px-6 py-4">{{ participant.re }}</td>
                                            <td class="px-6 py-4">{{ participant.nome }}</td>
                                            <td class="px-6 py-4">{{ participant.funcao }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>s