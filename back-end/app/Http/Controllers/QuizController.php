<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizzesRequest;

use App\Models\Quizzes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizzes = DB::table('quizzes')
            ->join('categories', 'quizzes.category_id', '=', 'categories.id')
            ->select('quizzes.*', 'categories.name')
            ->get();

        return response()->json($quizzes, 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuizzesRequest $request)
    {

        $quizzes = new Quizzes();
        $quizzes->fill($request->all());


        $option1 = $quizzes->option1;
        $option2 = $quizzes->option2;
        $option3 = $quizzes->option3;
        $option4 = $quizzes->option4;
        $correctAnswer = $quizzes->correctAnswer;
        if ($option1 == $option2 || $option1 == $option3 || $option1 == $option4 || $option2 == $option3 || $option2 == $option4 || $option3 == $option4) {
            $statusCode = 404;
        } else if ($correctAnswer !== $option1 && $correctAnswer !== $option2 && $correctAnswer !== $option3 && $correctAnswer !== $option4) {
            $statusCode = 404;
        } else {
            $statusCode = 201;
            $quizzes->save();
        }

        if (!$quizzes) {
            $statusCode = 404;
        }
        $data = [$statusCode, $quizzes];

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quizzes = Quizzes::find($id);
        $statusCode = 200;
        if (!$quizzes) {
            $statusCode = 404;
        }

        return response()->json($quizzes, $statusCode);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $quizzes = Quizzes::find($id);
        $quizzes->fill($request->all());
        $option1 = $quizzes->option1;
        $option2 = $quizzes->option2;
        $option3 = $quizzes->option3;
        $option4 = $quizzes->option4;
        $correctAnswer = $quizzes->correctAnswer;
        if ($option1 == $option2 || $option1 == $option3 || $option1 == $option4 || $option2 == $option3 || $option2 == $option4 || $option3 == $option4) {
            $statusCode = 404;
        } else if ($correctAnswer !== $option1 && $correctAnswer !== $option2 && $correctAnswer !== $option3 && $correctAnswer !== $option4) {
            $statusCode = 404;
        } else {
            $statusCode = 201;
            $quizzes->save();
        }

        if (!$quizzes) {
            $statusCode = 404;
        }
        $data = [$statusCode, $quizzes];

        return response()->json($data);
    }


    public function destroy($id)
    {
        $quizzes = Quizzes::find($id);
        $message = "User not found";
        if ($quizzes) {
            $quizzes->delete();
            $message = "delete success";
            $statusCode = 200;
        }

        return response()->json($message, $statusCode);
    }

    public function showTest($id)
    {
        $test = DB::table('quizzes')
            ->join('categories', 'quizzes.category_id', '=', 'categories.id')
            ->select('quizzes.*', 'categories.name')
            ->where('quizzes.category_id', 'LIKE', $id)
            ->get();

        return response()->json($test);
    }

    public function showQuizByCategoryId($id)
    {
        $quizzes = DB::table('quizzes')->where('category_id', '=', $id)->get();
        return response()->json($quizzes, 200);
    }

    public function searchByCategory($key)
    {
        $quizzes = DB::table('quizzes')->join('categories', 'quizzes.category_id', '=', 'categories.id')
            ->where('categories.name', 'LIKE', '%' . $key . '%')
            ->select('quizzes.*','categories.name')
            ->get();
        return response()->json($quizzes, 200);
    }
}
