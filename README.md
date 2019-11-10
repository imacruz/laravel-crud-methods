# Parameterizing CRUD methods for simple model's
## Parametrizando CRUD métodos para modelos simples

### To using this repository

 1. Look and copy the file Controller.php
 
 2. Defining the builder of your controller like
```php
    public function __construct()
    {
        $this->model = "App\Conta";
        $this->controller = "conta";
        $this->perPage = 10;
        $this->validationsRules = array(
            'nome' => 'required|min:3|max:50',
        );

        $this->validationMensages = array(
            'required' => 'É necessário informar :attribute.',
            'min' => 'Deve conter no mínimo 5 caracteres.',
            'max' => 'Deve conter no máximo 50 caracteres.',

        );
        
        $this->validationAttributes = array(
            'nome' => 'nome'
        );
    }
    
