<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    QuestionnaireController,
    AcademicYearController,
    DepartmentController,
    RoleController,
    EvaluationController,
    EvaluationEntryController,
};

$controllerPath = "App\Http\Controllers";

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



// Public Routes
Route::group(['prefix'=> 'user_auth'], function () {
    Route::post('/register', [AuthController::class,'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/filter_role', [AuthController::class, 'filterRole']);
    Route::patch('/update_user', [AuthController::class, 'updateUser']);
    //User Logout
    
    Route::delete('/{user}/delete', [AuthController::class, 'destroy']);
});

Route::get('/getallusers', [AuthController::class,'index']);

// Add Roles
Route::get('/get_roles', [RoleController::class,'index']);
Route::post('/add_roles', [RoleController::class,'store']);

//Add Department
Route::group(['prefix' => 'departments', 'controller' => "{$controllerPath}\DepartmentController"], function () {
    Route::get('/', [DepartmentController::class,'index']);
    Route::post('/create', [DepartmentController::class,'store']);
});




// Protected Routes
// Route::group(['middleware' => ['auth:sanctum']], function () use ($controllerPath) {
    
    Route::group(['prefix' => 'admin'], function () use ($controllerPath) {
        Route::group(['prefix' => 'questionnaires', 'controller' => "{$controllerPath}\QuestionnaireController"], function () {
            Route::get('/', 'index');
            Route::post('/create', 'store');
            Route::get('/{questionnaire}/show', 'show');
            Route::patch('/{questionnaire}/update', 'update');
            Route::delete('/{questionnaire}/delete', 'destroy');
        });
        Route::group(['prefix' => 'criterias', 'controller' => "{$controllerPath}\CriteriaController"], function () {
            Route::get('/', 'index');
            Route::post('/create', 'store');
            Route::get('/{criteria}/show', 'show');
            Route::patch('/{criteria}/update', 'update');
            Route::delete('/{criteria}/delete', 'destroy');
        });
        Route::group(['prefix' => 'criteria-points', 'controller' => "{$controllerPath}\CriteriaPointController"], function () {
            Route::get('/', 'index');
            Route::post('/create', 'store');
            Route::get('/{criteriaPoint}/show', 'show');
            Route::patch('/{criteriaPoint}/update', 'update');
            Route::delete('/{criteriaPoint}/delete', 'destroy');
        });
        Route::group(['prefix' => 'questions', 'controller' => "{$controllerPath}\QuestionController"], function () {
            Route::get('/', 'index');
            Route::post('/create', 'store');
            Route::get('/{question}/show', 'show');
            Route::patch('/{question}/update', 'update');
            Route::delete('/{question}/delete', 'destroy');
        });
        Route::group(['prefix' => 'choices', 'controller' => "{$controllerPath}\ChoiceController"], function () {
            Route::post('/create', 'store');
            Route::get('/{choice}/show', 'show');
            Route::patch('/{choice}/update', 'update');
            Route::delete('/{choice}/delete', 'destroy');
        });
        Route::group(['prefix' => 'academic-years', 'controller' => "{$controllerPath}\AcademicYearController"], function () {
            Route::get('/', 'index');
            Route::post('/create', 'store');
            Route::get('/{academicYear}/show', 'show');
            Route::patch('/{academicYear}/update', 'update');
            Route::delete('/{academicYear}/delete', 'destroy');
            Route::post('/search', 'searchAcademicYear');
        });
        Route::group(['prefix' => 'reports', 'controller' => "{$controllerPath}\ReportController"], function () {
            Route::post('get_evaluation_report', 'getEvaluationReport');
            Route::post('fetch_faculties', 'fetchFaculties');
        });

        Route::group(['prefix' => 'faculty_instructors', 'controller' => "{$controllerPath}\FacultyInstructorController"], function () {
            Route::post('fetch_faculties', 'fetchFaculties');
        });
    });

    Route::group(['prefix' => 'evaluation', 'controller' => "{$controllerPath}\EvaluationController"], function () {
        Route::post('fetch-faculties', 'fetchFaculties');
        Route::get('fetch-questionnaires', 'fetchQuestionnaires');
        Route::get('/fetch-active-year', 'fetchActiveYear');
        Route::post('/update-coment', 'updateComment');
        Route::post('/update-status', 'updateStatus');
    });

    Route::group(['prefix' => 'evaluation-entries', 'controller' => "{$controllerPath}\EvaluationEntryController"], function () {
        Route::get('/', 'index');
        Route::post('/single-entry', 'singleEntry');
        Route::post('/create', 'store');
        Route::post('/entry-checker', 'entryExistenceChecker');
    });

    Route::group(['prefix' => 'evaluation-answers', 'controller' => "{$controllerPath}\EvaluationAnswerController"], function () {
        Route::get('/', 'index');
        Route::post('/create', 'store');
    });
//     Route::post('/logout', [AuthController::class, 'logout']);
// });