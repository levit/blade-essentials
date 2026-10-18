<div class="container-fluid my-2">
    <div class="row">
        <div class="col-12">
            
            {{-- 1. Mensagens de Sessão (Flash) --}}
            @if(isset($component) && is_object($component) && method_exists($component, 'flashMessages'))
                @foreach ($component->flashMessages() as $flash)
                    <div class="alert alert-{{ $flash['class'] }} alert-dismissible auto-dismiss fade show border-start-lg" role="alert">
                        <div class="d-flex align-items-center">
                            <div class="alert-icon-aside me-3 mt-2">
                                <x-misc.icone :icon="$flash['icon']" :color="$flash['class']" class="me-1" />
                            </div>
                            <div>
                                {{ $flash['message'] }}
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endforeach
            @else
                @foreach (Select\Components\Enums\MessageTypeEnum::cases() as $type)
                    @php
                        $flashMessage = session($type->value);
                        if (!$flashMessage && $type === Select\Components\Enums\MessageTypeEnum::ERROR) {
                            $flashMessage = session('error');
                        }
                        if (!$flashMessage && $type === Select\Components\Enums\MessageTypeEnum::SUCCESS) {
                            $flashMessage = session('status');
                        }
                    @endphp
                    @if($flashMessage)
                        <div class="alert alert-{{ $type->getClass() }} alert-dismissible auto-dismiss fade show border-start-lg" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon-aside me-3 mt-2">
                                    <x-misc.icone :icon="$type->getIcon()" :color="$type->getClass()" class="me-1" />
                                </div>
                                <div>
                                    {{ $flashMessage }}
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                @endforeach
            @endif

            {{-- 2. Erros de Validação do FormRequest ($errors) --}}
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-start-lg" role="alert">
                    <div class="d-flex">
                        <div class="alert-icon-aside me-3">
                            <x-misc.icone icon="alert-circle" color="danger" class="me-1 mt-1" />
                        </div>
                        <div>
                            <div class="fw-700">Por favor, corrija os seguintes erros:</div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            @endif

        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeoutMs = {{ (int)($timeoutMs ?? 7000) }};
        const autoDismissAlerts = document.querySelectorAll('.alert-dismissible.auto-dismiss');
        
        autoDismissAlerts.forEach(function(alert) {
            setTimeout(function() {
                if (alert && window.bootstrap && window.bootstrap.Alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, timeoutMs);
        });
    });
</script>
@endpush