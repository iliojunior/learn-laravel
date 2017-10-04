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