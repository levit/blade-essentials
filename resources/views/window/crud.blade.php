@php
    $form = $form ?? ($component->form ?? null);

    if (!isset($form)) {
        throw new \InvalidArgumentException("A variável 'form' deve ser fornecida para construção do formulário.");
    }

    if (!($form instanceof \Select\Components\Support\Forms\FormBuilder)) {
        throw new \InvalidArgumentException("A variável 'form' deve ter uma instância de uma classe do tipo Select\Components\Support\Forms\FormBuilder.");
    }
@endphp

@extends('layouts.app')

@section('title', $form->getTitle())

@section('content')

    <x-window.header :caption="$form->getTitle()" :icon="$form->getCaptionIcon()" />

    <x-window.content>        

        <x-form.form id="crudFormPrincipal" data-crud-form="true" :method="$form->getMethod()" :enctype="$form->getEnctype()" :csrf="$form->needsCsrf()">
            
            {{ $slot }}

        </x-form.form>

    </x-window.content>

    @push('scripts')
        <script>
            (function() {
                'use strict';
                function aplicarFocoPrimeiroCampo() {
                    const form = document.querySelector('form[data-crud-form="true"]') || document.querySelector('form');
                    if (!form) return;

                    // 1. Procura o primeiro campo editável de entrada (input, select, textarea)
                    const seletorCampos = [
                        'input:not([type="hidden"]):not([disabled]):not([readonly]):not([type="button"]):not([type="submit"]):not([type="reset"])',
                        'select:not([disabled]):not([readonly])',
                        'textarea:not([disabled]):not([readonly])'
                    ].join(', ');

                    let elementoAlvo = form.querySelector(seletorCampos);

                    // 2. Se não houver campos de entrada (ex: tela de confirmação/exclusão), busca botões interativos
                    if (!elementoAlvo) {
                        const seletorBotoes = [
                            'button:not([disabled]):not([data-bs-toggle]):not(.box-collapse-btn):not(.btn-close)',
                            'input[type="submit"]:not([disabled])',
                            'input[type="button"]:not([disabled])'
                        ].join(', ');

                        elementoAlvo = form.querySelector(seletorBotoes);
                    }

                    if (elementoAlvo && typeof elementoAlvo.focus === 'function') {
                        try {
                            elementoAlvo.focus({ preventScroll: false });
                            if (typeof elementoAlvo.select === 'function' && !['checkbox', 'radio', 'file', 'button', 'submit'].includes(elementoAlvo.type)) {
                                elementoAlvo.select();
                            }
                        } catch (e) {
                            // Silencioso em caso de restrições do navegador
                        }
                    }
                }

                function agendarFoco() {
                    aplicarFocoPrimeiroCampo();
                    setTimeout(aplicarFocoPrimeiroCampo, 50);
                    setTimeout(aplicarFocoPrimeiroCampo, 200);
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', agendarFoco);
                } else {
                    agendarFoco();
                }
            })();
        </script>
    @endpush

@endsection