<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Category;

class InsertPredefinedCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $categories = [
        [ 'name' => 'Allergy & Immunology' ],
        [ 'name' => 'Anesthesiology' ],
        [ 'name' => 'Audiology' ],
        [ 'name' => 'Cardiology' ],
        [ 'name' => 'Critical Care' ],
        [ 'name' => 'Dermatology' ],
        [ 'name' => 'Diabetes & Endocrinology' ],
        [ 'name' => 'Emergency Medicine' ],
        [ 'name' => 'Family Medicine' ],
        [ 'name' => 'Gastroenterology' ],
        [ 'name' => 'General Surgery' ],
        [ 'name' => 'Hematology-Oncology' ],
        [ 'name' => 'Infectious Diseases & HIV/AIDS' ],
        [ 'name' => 'Internal Medicine' ],
        [ 'name' => 'Nephrology' ],
        [ 'name' => 'Neurology' ],
        [ 'name' => 'OB/GYN' ],
        [ 'name' => 'Oncology' ],
        [ 'name' => 'Ophthalmology' ],
        [ 'name' => 'Orthopedics' ],
        [ 'name' => 'Pathology & Lab Medicine' ],
        [ 'name' => 'Pediatrics' ],
        [ 'name' => 'Plastic Surgery' ],
        [ 'name' => 'Psychiatry' ],
        [ 'name' => 'Public Health' ],
        [ 'name' => 'Pulmonary Medicine' ],
        [ 'name' => 'Radiology' ],
        [ 'name' => 'Rheumatology' ],
        [ 'name' => 'Transplantation' ],
        [ 'name' => 'Speech Pathology' ],
        [ 'name' => 'Urology' ],
        [ 'name' => 'Women\'s Health' ]
      ];
      foreach ($categories as $category) {
        Category::create($category);
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
