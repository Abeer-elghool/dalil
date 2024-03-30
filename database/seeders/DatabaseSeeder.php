<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Answer;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Author;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\ContactUs;
use App\Models\Lesson;
use App\Models\Mcq;
use App\Models\Photo;
use App\Models\PowerPoint;
use App\Models\Question;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin::create([
        //     'name'      => 'Dalel-admin',
        //     'uuid'      => 'd7961c75-7b66-40c3-0000-e7b78ddf7d22',
        //     'password'  => '123456789',
        //     'email'     => 'admin@dalel.com',
        //     'phone'     => '123456789',
        //     'active'    => true,
        //     'user_type' => 'super_admin',
        // ]);

        // Book::create([
        //     'title' => 'book',
        //     'desc'  => 'description'
        // ]);

        // Section::create([
        //     'title'     => 'section',
        //     'desc'      => 'description',
        //     'book_id'   => 1
        // ]);

        // Chapter::create([
        //     'title'         => 'Chapter',
        //     'desc'          => 'description',
        //     'section_id'    => 1
        // ]);

        // Lesson::create([
        //     'title'      => 'Lesson',
        //     'desc'       => 'description',
        //     'chapter_id' => 1,
        //     'section_id' => 1
        // ]);

        // Question::create([
        //     'uuid'             => 'd7961c75-7b66-40c3-a7e4-e7b78ddf7d22',
        //     'title'            => 'Question',
        //     'lesson_id'        => 1,
        //     'section_id'       => 1,
        //     'chapter_id'       => 1
        // ]);

        // Answer::create(
        //     [
        //         'answer'       => 'Answer One.',
        //         'is_correct'   => 1,
        //         'question_id'  => 1
        //     ],
        //     [
        //         'answer'       => 'Answer Two.',
        //         'is_correct'   => 1,
        //         'question_id'  => 1
        //     ],
        //     [
        //         'answer'       => 'Answer Three.',
        //         'is_correct'   => 1,
        //         'question_id'  => 1
        //     ],
        //     [
        //         'answer'       => 'Answer Four.',
        //         'is_correct'   => 1,
        //         'question_id'  => 1
        //     ]
        // );

        // Mcq::create(
        //     [
        //         'uuid'            => 'f8a9f40f-a047-4c78-888f-e519d76d1e00',
        //         'title'           => 'Sample MCQ',
        //         'questions_count' => 10,
        //         'duration'        => 2,
        //         'questions_type'  => 'random'
        //     ]
        // );

        // ArticleCategory::create(
        //     [
        //         'uuid'  => 'f8a9f40f-a047-4c78-0000-e519d76d1e00',
        //         'title' => 'Test Category',
        //         'desc'  => 'This is a test category.',
        //         'file'  => null,
        //         'active' => true
        //     ]
        // );

        // Article::create(
        //     [
        //         'uuid'  => 'f8a9f40f-a047-0000-0000-e519d76d1e00',
        //         'title'  => 'Test Article',
        //         'body'   => 'This is a test article.',
        //         'link'   => 'https://youtu.be/2MCMNWlOVI0',
        //         'file'   => null,
        //         'active' => true,
        //         'article_category_id' => 1,
        //         'admin_id' => 1
        //     ]
        // );

        // PowerPoint::create(
        //     [
        //         'uuid'  => 'f8a9f40f-0000-0000-0000-e519d76d1e00',
        //         'title'  => 'Test PowerPoint',
        //         'desc'   => 'This is a test PowerPoint.',
        //         'link'   => 'https://youtu.be/2MCMNWlOVI0',
        //         'file'   => null,
        //         'active' => true,
        //         'admin_id' => 1
        //     ]
        // );

        // Photo::create(
        //     [
        //         'uuid'  => 'f8a9f40f-0000-0000-0000-e519d76d1e00',
        //         'title'  => 'Test PowerPoint',
        //         'desc'   => 'This is a test PowerPoint.',
        //         'file'   => 'https://upload.wikimedia.org/wikipedia/commons/7/70/Example.png',
        //         'active' => true,
        //         'admin_id' => 1
        //     ]
        // );

        // ContactUs::create(
        //     [
        //         'key'    => 'email',
        //         'value'  => 'dalil@gmail.com',
        //         'file'   => 'https://upload.wikimedia.org/wikipedia/commons/7/70/Example.png',
        //         'active' => true,
        //         'admin_id' => 1
        //     ]
        // );

        // Author::create(
        //     [
        //         'uuid'      => 'f8a9f40f-0000-0000-0000-e519d76d1e00',
        //         'name'      => 'Abeer Mohamed Ali',
        //         'position'  => 'CEO',
        //         'about'     => 'As the CEO, Abeer worked tirelessly to grow the company and make it a major player in the tech industry. She was a visionary leader who always had her sights set on the future, and she was constantly looking for new and innovative ways to push the company forward.',
        //         'active'    => true,
        //         'file'      => 'https://upload.wikimedia.org/wikipedia/commons/7/70/Example.png',
        //         'admin_id'  => 1
        //     ]
        // );

        // User::create(
        //     [
        //         'name'      => 'abeer',
        //         'email'     => 'abeer@gmail.com',
        //         'password'  => '123123',
        //         'image'     => 'https://upload.wikimedia.org/wikipedia/commons/7/70/Example.png',
        //         'gender'    => 'female'
        //     ]
        // );

        /**
         * you have to run the above seeders first
         */

        $this->call(UserSeeder::class);
    }
}
