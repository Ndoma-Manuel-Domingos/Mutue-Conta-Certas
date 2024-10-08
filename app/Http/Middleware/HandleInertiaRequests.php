<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Config;
use App\Models\Empresa;
use App\Models\Exercicio;
use App\Models\Periodo;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{

    use Config;
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'sessions' => [
                'empresa_sessao' => Empresa::with(["contactos", "documentos", "tipo", "grupo", "regime", "endereco", "moeda.base"])->find($this->empresaLogada()), // Session::get('empresa_logada_mutue_contas_certas_2024'),
                'exercicio_sessao' => Exercicio::find($this->exercicioActivo()), //  Session::get('exercicio_logada_mutue_contas_certas_2024'),
                'periodo_sessao' => Periodo::find($this->periodoActivo()), //  Session::get('exercicio_logada_mutue_contas_certas_2024'),
            ],
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                ];
            },
        ]);
    }
}
