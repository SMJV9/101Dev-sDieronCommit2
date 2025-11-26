<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pregunta 1: Frutas
        $question1 = Question::create([
            'name' => 'Frutas favoritas',
            'question_text' => '¿Qué fruta comen más los niños?',
            'category' => 'general',
            'is_active' => true,
        ]);

        Answer::create(['question_id' => $question1->id, 'answer_text' => 'Manzana', 'points' => 32, 'order' => 1]);
        Answer::create(['question_id' => $question1->id, 'answer_text' => 'Plátano', 'points' => 25, 'order' => 2]);
        Answer::create(['question_id' => $question1->id, 'answer_text' => 'Naranja', 'points' => 18, 'order' => 3]);
        Answer::create(['question_id' => $question1->id, 'answer_text' => 'Fresa', 'points' => 12, 'order' => 4]);
        Answer::create(['question_id' => $question1->id, 'answer_text' => 'Uva', 'points' => 8, 'order' => 5]);
        Answer::create(['question_id' => $question1->id, 'answer_text' => 'Sandía', 'points' => 5, 'order' => 6]);

        // Pregunta 2: Lenguajes de programación
        $question2 = Question::create([
            'name' => 'Lenguajes de programación',
            'question_text' => '¿Cuál es el primer lenguaje que aprenden los programadores?',
            'category' => 'dev',
            'is_active' => true,
        ]);

        Answer::create(['question_id' => $question2->id, 'answer_text' => 'Python', 'points' => 40, 'order' => 1]);
        Answer::create(['question_id' => $question2->id, 'answer_text' => 'JavaScript', 'points' => 28, 'order' => 2]);
        Answer::create(['question_id' => $question2->id, 'answer_text' => 'Java', 'points' => 15, 'order' => 3]);
        Answer::create(['question_id' => $question2->id, 'answer_text' => 'C++', 'points' => 10, 'order' => 4]);
        Answer::create(['question_id' => $question2->id, 'answer_text' => 'HTML', 'points' => 5, 'order' => 5]);
        Answer::create(['question_id' => $question2->id, 'answer_text' => 'C', 'points' => 2, 'order' => 6]);

        // Pregunta 3: Editores de código
        $question3 = Question::create([
            'name' => 'Editores de código',
            'question_text' => '¿Qué editor de código usan más los developers?',
            'category' => 'dev',
            'is_active' => true,
        ]);

        Answer::create(['question_id' => $question3->id, 'answer_text' => 'VS Code', 'points' => 50, 'order' => 1]);
        Answer::create(['question_id' => $question3->id, 'answer_text' => 'IntelliJ IDEA', 'points' => 20, 'order' => 2]);
        Answer::create(['question_id' => $question3->id, 'answer_text' => 'Sublime Text', 'points' => 12, 'order' => 3]);
        Answer::create(['question_id' => $question3->id, 'answer_text' => 'Vim', 'points' => 10, 'order' => 4]);
        Answer::create(['question_id' => $question3->id, 'answer_text' => 'Notepad++', 'points' => 5, 'order' => 5]);
        Answer::create(['question_id' => $question3->id, 'answer_text' => 'Atom', 'points' => 3, 'order' => 6]);

        // Pregunta 4: Frameworks JavaScript
        $question4 = Question::create([
            'name' => 'Frameworks JavaScript',
            'question_text' => '¿Qué framework de JavaScript es más popular?',
            'category' => 'dev',
            'is_active' => true,
        ]);

        Answer::create(['question_id' => $question4->id, 'answer_text' => 'React', 'points' => 45, 'order' => 1]);
        Answer::create(['question_id' => $question4->id, 'answer_text' => 'Vue.js', 'points' => 25, 'order' => 2]);
        Answer::create(['question_id' => $question4->id, 'answer_text' => 'Angular', 'points' => 18, 'order' => 3]);
        Answer::create(['question_id' => $question4->id, 'answer_text' => 'Next.js', 'points' => 7, 'order' => 4]);
        Answer::create(['question_id' => $question4->id, 'answer_text' => 'Svelte', 'points' => 3, 'order' => 5]);
        Answer::create(['question_id' => $question4->id, 'answer_text' => 'jQuery', 'points' => 2, 'order' => 6]);

        // Pregunta 5: Sistemas operativos
        $question5 = Question::create([
            'name' => 'Sistemas operativos',
            'question_text' => '¿Qué sistema operativo prefieren los programadores?',
            'category' => 'dev',
            'is_active' => true,
        ]);

        Answer::create(['question_id' => $question5->id, 'answer_text' => 'Windows', 'points' => 35, 'order' => 1]);
        Answer::create(['question_id' => $question5->id, 'answer_text' => 'macOS', 'points' => 30, 'order' => 2]);
        Answer::create(['question_id' => $question5->id, 'answer_text' => 'Linux/Ubuntu', 'points' => 25, 'order' => 3]);
        Answer::create(['question_id' => $question5->id, 'answer_text' => 'Arch Linux', 'points' => 6, 'order' => 4]);
        Answer::create(['question_id' => $question5->id, 'answer_text' => 'Debian', 'points' => 3, 'order' => 5]);
        Answer::create(['question_id' => $question5->id, 'answer_text' => 'Fedora', 'points' => 1, 'order' => 6]);

        // Pregunta 6: Comida rápida
        $question6 = Question::create([
            'name' => 'Comida rápida',
            'question_text' => '¿Qué comida rápida pides cuando programas de noche?',
            'category' => 'general',
            'is_active' => true,
        ]);

        Answer::create(['question_id' => $question6->id, 'answer_text' => 'Pizza', 'points' => 42, 'order' => 1]);
        Answer::create(['question_id' => $question6->id, 'answer_text' => 'Hamburguesa', 'points' => 28, 'order' => 2]);
        Answer::create(['question_id' => $question6->id, 'answer_text' => 'Tacos', 'points' => 15, 'order' => 3]);
        Answer::create(['question_id' => $question6->id, 'answer_text' => 'Sushi', 'points' => 8, 'order' => 4]);
        Answer::create(['question_id' => $question6->id, 'answer_text' => 'Pollo frito', 'points' => 5, 'order' => 5]);
        Answer::create(['question_id' => $question6->id, 'answer_text' => 'Sandwiches', 'points' => 2, 'order' => 6]);
    }
}
