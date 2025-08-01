<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { onMounted } from 'vue';

// Recebemos a APR a ser editada como uma propriedade (prop) do controller
const props = defineProps({
    apr: Object,
});

// Usamos o 'useForm' do Inertia, inicializando-o com os dados da APR existente
const form = useForm({
    _method: 'PUT', // Informa ao Laravel que esta é uma requisição de atualização
    ...props.apr, // Preenche o formulário com todos os dados da APR
});

// Funções para adicionar e remover linhas dinamicamente (iguais ao Create.vue)
const addItem = () => form.items.push({ tarefa: '', risco: '', recomendacao: '' });
const removeItem = (index) => form.items.splice(index, 1);
const addParticipant = () => form.participants.push({ re: '', nome: '', funcao: '' });
const removeParticipant = (index) => form.participants.splice(index, 1);

// Função para formatar a data para o input datetime-local
const formatDateTimeForInput = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    // Ajusta para o fuso horário local para evitar problemas de conversão
    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
    return date.toISOString().slice(0, 16);
};

// Quando o componente é montado, formatamos as datas para o formato correto
onMounted(() => {
    form.inicio_at = formatDateTimeForInput(props.apr.inicio_at);
    form.termino_at = formatDateTimeForInput(props.apr.termino_at);
});


// Função para submeter o formulário de atualização
const submit = () => {
    form.post(route('aprs.update', props.apr.id));
};
</script>

<template>
    <Head title="Editar APR" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Análise Preliminar de Risco: {{ form.numero_apr }}</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- O formulário é praticamente idêntico ao de criação, mas aponta para a rota 'update' -->
                    <form @submit.prevent="submit" class="p-6 space-y-6">
                        <!-- Seção 1: Dados Gerais -->
                        <section>
                            <h3 class="text-lg font-medium text-gray-900">Dados Gerais</h3>
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="numero_apr" value="Nº da APR" />
                                    <TextInput id="numero_apr" type="text" class="mt-1 block w-full" v-model="form.numero_apr" required />
                                    <InputError class="mt-2" :message="form.errors.numero_apr" />
                                </div>
                                <div>
                                    <InputLabel for="unidade_nome" value="Nome da Unidade / UT" />
                                    <TextInput id="unidade_nome" type="text" class="mt-1 block w-full" v-model="form.unidade_nome" required />
                                    <InputError class="mt-2" :message="form.errors.unidade_nome" />
                                </div>
                                <div>
                                    <InputLabel for="responsavel_unidade" value="Responsável pela Unidade" />
                                    <TextInput id="responsavel_unidade" type="text" class="mt-1 block w-full" v-model="form.responsavel_unidade" required />
                                    <InputError class="mt-2" :message="form.errors.responsavel_unidade" />
                                </div>
                                <div>
                                    <InputLabel for="local" value="Local" />
                                    <TextInput id="local" type="text" class="mt-1 block w-full" v-model="form.local" required />
                                    <InputError class="mt-2" :message="form.errors.local" />
                                </div>
                                <div>
                                    <InputLabel for="inicio_at" value="Data e Hora de Início" />
                                    <TextInput id="inicio_at" type="datetime-local" class="mt-1 block w-full" v-model="form.inicio_at" required />
                                    <InputError class="mt-2" :message="form.errors.inicio_at" />
                                </div>
                                <div>
                                    <InputLabel for="termino_at" value="Data e Hora de Término" />
                                    <TextInput id="termino_at" type="datetime-local" class="mt-1 block w-full" v-model="form.termino_at" required />
                                    <InputError class="mt-2" :message="form.errors.termino_at" />
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <InputLabel for="atividade_descricao" value="Descrição da Atividade" />
                                    <textarea id="atividade_descricao" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="form.atividade_descricao" rows="3"></textarea>
                                    <InputError class="mt-2" :message="form.errors.atividade_descricao" />
                                </div>
                            </div>
                        </section>
                        <!-- Seção 2: Itens de Risco -->
                        <section>
                            <h3 class="text-lg font-medium text-gray-900">Itens de Risco</h3>
                             <div v-for="(item, index) in form.items" :key="index" class="mt-4 p-4 border rounded-md relative">
                                <span class="font-bold">Item {{ index + 1 }}</span>
                                <div class="grid grid-cols-1 gap-6 mt-2">
                                    <div>
                                        <InputLabel :for="'tarefa_'+index" value="Tarefa" />
                                        <textarea :id="'tarefa_'+index" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" v-model="item.tarefa" rows="2" required></textarea>
                                    </div>
                                    <div>
                                        <InputLabel :for="'risco_'+index" value="Risco" />
                                        <textarea :id="'risco_'+index" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" v-model="item.risco" rows="2" required></textarea>
                                    </div>
                                    <div>
                                        <InputLabel :for="'recomendacao_'+index" value="Recomendação" />
                                        <textarea :id="'recomendacao_'+index" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" v-model="item.recomendacao" rows="2" required></textarea>
                                    </div>
                                </div>
                                <button type="button" @click="removeItem(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold">X</button>
                            </div>
                            <PrimaryButton type="button" @click="addItem" class="mt-4">Adicionar Item</PrimaryButton>
                            <InputError class="mt-2" :message="form.errors.items" />
                        </section>
                        <!-- Seção 3: Participantes -->
                        <section>
                             <h3 class="text-lg font-medium text-gray-900">Participantes</h3>
                            <div v-for="(participant, index) in form.participants" :key="index" class="mt-4 p-4 border rounded-md relative">
                                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                     <div>
                                         <InputLabel :for="'re_'+index" value="RE" />
                                         <TextInput :id="'re_'+index" type="text" class="mt-1 block w-full" v-model="participant.re" required />
                                     </div>
                                     <div>
                                         <InputLabel :for="'nome_'+index" value="Nome" />
                                         <TextInput :id="'nome_'+index" type="text" class="mt-1 block w-full" v-model="participant.nome" required />
                                     </div>
                                     <div>
                                         <InputLabel :for="'funcao_'+index" value="Função" />
                                         <TextInput :id="'funcao_'+index" type="text" class="mt-1 block w-full" v-model="participant.funcao" required />
                                     </div>
                                 </div>
                                 <button type="button" @click="removeParticipant(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold">X</button>
                            </div>
                            <PrimaryButton type="button" @click="addParticipant" class="mt-4">Adicionar Participante</PrimaryButton>
                             <InputError class="mt-2" :message="form.errors.participants" />
                        </section>
                        <!-- Seção 4: Checklist -->
                        <section>
                            <h3 class="text-lg font-medium text-gray-900">Checklist Final</h3>
                            <div class="mt-4 space-y-2">
                                <label class="flex items-center">
                                    <Checkbox v-model:checked="form.checklist_conhece_atividade" />
                                    <span class="ms-2 text-sm text-gray-600">Conhece a atividade a ser executada?</span>
                                </label>
                                <label class="flex items-center">
                                    <Checkbox v-model:checked="form.checklist_sabe_onde_encontrar" />
                                    <span class="ms-2 text-sm text-gray-600">Se algo me ocorrer, outras pessoas sabem onde me encontrar?</span>
                                </label>
                                 <label class="flex items-center">
                                    <Checkbox v-model:checked="form.checklist_acoes_tomadas" />
                                    <span class="ms-2 text-sm text-gray-600">Todas as ações mencionadas foram tomadas?</span>
                                </label>
                            </div>
                        </section>
                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">Atualizar APR</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>