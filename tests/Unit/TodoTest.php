<?php

namespace Tests\Unit;

use App\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    public function testRetornando200EUmaListaDeTodos()
    {
        $dataFactory = factory(Todo::class, 15)->create();
        $response = $this->json('GET', '/api/todos');

        $keys = array_keys($dataFactory->first()->toArray());

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $keys
                ]
            ]);
    }

    public function testGravarUmTodoERecuperarPorUmTermo()
    {
        $newTodo = new Todo();
        $newTodo->name = "Identificado TODO";
        $newTodo->description = "Uma descrição do TODO com bastante palavras para testar";
        $newTodo->save();

        $factoryData = factory(Todo::class, 14)->create();

        $response = $this->json('GET', '/api/todos?search=Identificado');

        $response
            ->assertStatus(200)
            ->assertJsonFragment($newTodo->toArray());
    }

    public function testRecuperarUmTodoPorId()
    {
        $newTodo = factory(Todo::class)->create();

        $response = $this->json('GET', '/api/todos/' . $newTodo->id);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($newTodo->toArray());
    }

    public function testRecuperarUmTodoPorIdInexistente()
    {
        $response = $this->json('GET', '/api/todos/999');

        //404 -> Not found
        $response
            ->assertStatus(404);
    }

    public function testRecuperarComOrdenacaoAscPorName()
    {
        $todo1 = new  Todo();
        $todo1->name = "zzz";
        $todo1->description = "batata";
        $todo1->save();

        $todo2 = new  Todo();
        $todo2->name = "aaa";
        $todo2->description = "batata";
        $todo2->save();

        $response = $this->json('GET', '/api/todos?sortBy=name&descending=false');

        $response
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    '0' => [
                        'name' => 'aaa',
                        'description' => 'batata'
                    ]
                ]
            ]);
    }

    public function testGravandoUmNovoTodo()
    {
        //Criando o TODO
        $newTodo = new Todo();
        $newTodo->name = "Nome de um novo TODO";
        $newTodo->description = "Uma descrição do TODO com bastante palavras para testar";

        //Tem que converter para array de JSON para postar
        $newTodoArray = $newTodo->toArray();

        //Fazendo HTTP request do tipo POST, url = /api/todos
        $response = $this->json('POST', '/api/todos', $newTodoArray);

        //Asserção
        $response
            ->assertStatus(201)
            ->assertJsonFragment($newTodo->toArray());
    }

    public function testGravandoUmTodoVazio()
    {
        $newTodo = new Todo();

        $response = $this->json('POST', '/api/todos', $newTodo->toArray());

        //Asserta 422 -> Entidade não pode ser convertida
        $response
            ->assertStatus(422);
    }

    public function testGravarUmTodoERecuperarOTodoCriado()
    {
        $newTodo = factory(Todo::class)->create();
        $newTodoArray = $newTodo->toArray();

        $response = $this->json('GET', '/api/todos', $newTodoArray);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($newTodoArray);
    }

    public function testGravarUmTodoEApagarOTodoCriado()
    {
        //Criando um novo TODO
        $newTodo = factory(Todo::class)->create();

        //Fazendo requisição de DELETE
        $responseDelete = $this->json('DELETE', '/api/todos/' . $newTodo->id);

        //Assertando resposta do delete
        $responseDelete
            ->assertStatus(204);

        //Buscar no banco se existe TODO com o id do que eu criei
        $testDatabase = Todo::find($newTodo->id);

        //Asserta que o resultado do banco foi NULO
        $this->assertNull($testDatabase);
    }
}
