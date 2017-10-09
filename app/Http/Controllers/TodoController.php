<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        //Termo da pesquisa passado no field 'search'
        $searchTerm = $request->get('search');

        //Limite de item por pagina no field 'limit'
        $limit = $request->get('limit');

        $sortBy = $request->get('sortBy');
        $descending = $request->get('descending');

        //Faz o get de todos os TODOS de acordo com a busca
        $listData = Todo::search($searchTerm);

        if ($sortBy) {
            $listData = $listData->orderBy($sortBy, $descending == 'true' ? 'desc' : 'asc');
        }

        //Faz a paginação dos TODOS
        $listData = $listData->paginate($limit ?: 15);

        //Responde como json
        return response()->json($listData);
    }

    public function store(TodoRequest $request)
    {
        //$this que contém a instância que salva o modelo
        return $this->save(new Todo(), $request);
    }

    private function save(Todo $todo, TodoRequest $request)
    {
        //Atribui os valores para $todo
        $todo->name = $request->json('name');
        $todo->description = $request->json('description');

        //Salva o modelo
        $todo->save();

        //Faz um get no $todo
        return $this->show($todo);
    }

    public function show(Todo $todo)
    {
        //Retorna o $todo com status 201 se acabou de ser criado ou 200 se já estava criado
        return response()->json($todo, $todo->wasRecentlyCreated ? 201 : 200);
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response(null, 204);
    }
}
