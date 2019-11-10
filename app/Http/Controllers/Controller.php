<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Endereço da Model
     * @var string
     */
    protected $model;

    /**
     * Nome do controlador
     * @var string
     */
    protected $controller;

    /**
     * Resultados exebidos por página
     * @var int
    */
    protected $perPage;

    /**
     * Regras parada validação
     * @var array
     */
    protected $validationsRules;

    /**
     * Customiza as mensagens da validação
     * @var array
     */
    protected $validationMensages;

    /**
     * Customiza nome dos atributos exibidos 
     * na validação
     * @var array
     */
    protected $validationAttributes;

    /** 
     * @return view|objects
    */
    public function index()
    {
        $data = $this->model::paginate(10);

        if($this->multipleAccount){
            if(Auth::user()->isAdmin())
                $data = $this->model::paginate($this->perPage);
            else
                $data = $this->model::where('conta_id', Auth::user()->conta_id)
                                    ->paginate($this->perPage);
        }
            
        return view($this->controller.'.index', compact('data'));
    }

    /**
     * @param id
     * @param request
     * @return view|object
     */
    public function edit($id, Request $request)
    {
        $data = $this->model::find($id);

        if(!$data)
            return redirect($this->controller);
        
        if($request->isMethod('POST'))
        {
            $validator = Validator::make($request->all(), 
                                         $this->validationsRules, 
                                         $this->validationMensages, 
                                         $this->validationAttributes);

            if ($validator->fails()) {
                return  back()
                        ->withErrors($validator)
                        ->withInput();
            }

            $data->update($request->all());

            return redirect($this->controller)
                   ->with('message', 'Registro atualizado com sucesso!');
        }

        return view($this->controller.'.edit', compact('data'));
    }

    /**
     * @param request
     * @return view|object
     */
    public function create(Request $request)
    {
        $model = new $this->model;

        if($request->isMethod('POST'))
        {
            $validator = Validator::make($request->all(), 
                                         $this->validationsRules, 
                                         $this->validationMensages, 
                                         $this->validationAttributes);

            if ($validator->fails()) {
                return  back()
                        ->withErrors($validator)
                        ->withInput();
            }

           $this->model::create($request->all());
           
           return redirect($this->controller)
                  ->with('message', 'Cadastro realizado com sucesso!');
        }

        return view($this->controller.'.create');
    }

    /**
     * @param id
     */
    public function delete($id)
    {
        $data = $this->model::find($id);

        if(!$data)
            return redirect($this->controller);
        
        $data->delete();

        return redirect($this->controller)
                  ->with('message', 'Registro removido com sucesso!');
    }

}
