<?php

// routes/api.php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\QuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);

    // User management
    Route::put('/user', [UserController::class, 'updateUser']);

    // Recipe management
    Route::post('/recipes/generate', [RecipeController::class, 'generateRecipes']);
    Route::post('/recipes', [RecipeController::class, 'createRecipe']);
    Route::delete('/recipes/{id}', [RecipeController::class, 'deleteRecipe']);

    // Plan management
    Route::post('create/plan', [PlanController::class, 'createPlan']);
    Route::delete('/plans/{id}', [PlanController::class, 'deletePlan']);
    Route::put('/plans/{id}', [PlanController::class, 'updatePlan']);

    // Question management
    Route::post('/questions', [QuestionController::class, 'askQuestion']);

});

/*
# Complete API Route Examples

## 1. User Registration

### Request
```http
POST /api/register
Content-Type: application/json

{
  "name": "Maria Rodriguez",
  "email": "maria.rodriguez@example.com",
  "password": "SecurePass123!",
  "weight": 65.5,
  "height": 168.0,
  "age": 28,
  "goal": "weight loss",
  "ingredients": [
    "chicken breast",
    "brown rice",
    "broccoli",
    "olive oil",
    "garlic",
    "onions",
    "tomatoes",
    "bell peppers"
  ],
  "dietary_preferences": [
    "gluten-free",
    "low-carb"
  ],
  "allergies": [
    "nuts",
    "shellfish"
  ],
  "kitchen_equipment": [
    "oven",
    "stovetop",
    "blender",
    "air fryer",
    "microwave"
  ],
  "language": "en"
}
```

### Response
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "Maria Rodriguez",
      "email": "maria.rodriguez@example.com",
      "weight": 65.5,
      "height": 168.0,
      "age": 28,
      "goal": "weight loss",
      "ingredients": [
        "chicken breast",
        "brown rice",
        "broccoli",
        "olive oil",
        "garlic",
        "onions",
        "tomatoes",
        "bell peppers"
      ],
      "dietary_preferences": [
        "gluten-free",
        "low-carb"
      ],
      "allergies": [
        "nuts",
        "shellfish"
      ],
      "kitchen_equipment": [
        "oven",
        "stovetop",
        "blender",
        "air fryer",
        "microwave"
      ],
      "recipe_tokens": 10,
      "question_tokens": 10,
      "language": "en",
      "email_verified_at": null,
      "created_at": "2025-01-15T10:30:00.000000Z",
      "updated_at": "2025-01-15T10:30:00.000000Z"
    },
    "token": "1|abc123def456ghi789jkl012mno345pqr678stu901vwx234yz"
  }
}
```

---

## 2. User Login

### Request
```http
POST /api/login
Content-Type: application/json

{
  "email": "maria.rodriguez@example.com",
  "password": "SecurePass123!"
}
```

### Response
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "Maria Rodriguez",
      "email": "maria.rodriguez@example.com",
      "weight": 65.5,
      "height": 168.0,
      "age": 28,
      "goal": "weight loss",
      "ingredients": [
        "chicken breast",
        "brown rice",
        "broccoli",
        "olive oil"
      ],
      "dietary_preferences": [
        "gluten-free",
        "low-carb"
      ],
      "allergies": [
        "nuts",
        "shellfish"
      ],
      "kitchen_equipment": [
        "oven",
        "stovetop",
        "blender",
        "air fryer"
      ],
      "recipe_tokens": 8,
      "question_tokens": 7,
      "language": "en",
      "created_at": "2025-01-15T10:30:00.000000Z",
      "updated_at": "2025-01-15T14:20:00.000000Z",
      "recipes": [
        {
          "id": 1,
          "user_id": 1,
          "name": "Grilled Chicken with Roasted Vegetables",
          "description": "Healthy low-carb meal with lean protein and fiber-rich vegetables",
          "time": 35,
          "servings": 2,
          "ingredients": [
            {
              "ing": "300g chicken breast, sliced",
              "have": true
            },
            {
              "ing": "1 cup broccoli florets",
              "have": true
            },
            {
              "ing": "1 bell pepper, sliced",
              "have": true
            },
            {
              "ing": "2 tbsp olive oil",
              "have": true
            }
          ],
          "instructions": [
            "Preheat oven to 200°C (400°F)",
            "Season chicken with salt and pepper",
            "Toss vegetables with 1 tbsp olive oil",
            "Grill chicken for 6-7 minutes per side",
            "Roast vegetables for 20 minutes",
            "Serve hot"
          ],
          "created_at": "2025-01-15T12:15:00.000000Z",
          "updated_at": "2025-01-15T12:15:00.000000Z",
          "questions": [
            {
              "id": 1,
              "question": "Can I substitute chicken with tofu for a vegetarian version?",
              "answer": "Yes! You can substitute chicken with firm tofu. Press the tofu to remove excess water, then marinate it in your favorite seasonings for at least 30 minutes before grilling. Cook for about 4-5 minutes per side until golden brown.",
              "recipe_id": 1,
              "created_at": "2025-01-15T13:45:00.000000Z",
              "updated_at": "2025-01-15T13:45:00.000000Z"
            }
          ]
        }
      ],
      "plans": [
        {
          "id": 1,
          "name": "Dinner Tonight",
          "date": "2025-01-15T19:00:00.000000Z",
          "recipe_id": 1,
          "user_id": 1,
          "created_at": "2025-01-15T12:20:00.000000Z",
          "updated_at": "2025-01-15T12:20:00.000000Z",
          "recipe": {
            "id": 1,
            "name": "Grilled Chicken with Roasted Vegetables",
            "description": "Healthy low-carb meal with lean protein and fiber-rich vegetables",
            "time": 35,
            "servings": 2
          }
        }
      ]
    },
    "token": "2|xyz789abc012def345ghi678jkl901mno234pqr567stu890vwx"
  }
}
```

---

## 3. User Logout

### Request
```http
POST /api/logout
Authorization: Bearer 2|xyz789abc012def345ghi678jkl901mno234pqr567stu890vwx
Content-Type: application/json
```

### Response
```json
{
  "success": true,
  "data": {
    "message": "Logged out successfully"
  }
}
```

---

## 4. Update User Profile

### Request
```http
PUT /api/user
Authorization: Bearer 2|xyz789abc012def345ghi678jkl901mno234pqr567stu890vwx
Content-Type: application/json

{
  "weight": 64.2,
  "goal": "muscle building",
  "ingredients": [
    "chicken breast",
    "brown rice",
    "broccoli",
    "olive oil",
    "eggs",
    "greek yogurt",
    "quinoa"
  ]
}
```

### Response
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Maria Rodriguez",
    "email": "maria.rodriguez@example.com",
    "weight": 64.2,
    "height": 168.0,
    "age": 28,
    "goal": "muscle building",
    "ingredients": [
      "chicken breast",
      "brown rice",
      "broccoli",
      "olive oil",
      "eggs",
      "greek yogurt",
      "quinoa"
    ],
    "dietary_preferences": [
      "gluten-free",
      "low-carb"
    ],
    "allergies": [
      "nuts",
      "shellfish"
    ],
    "kitchen_equipment": [
      "oven",
      "stovetop",
      "blender",
      "air fryer"
    ],
    "recipe_tokens": 8,
    "question_tokens": 7,
    "language": "en",
    "created_at": "2025-01-15T10:30:00.000000Z",
    "updated_at": "2025-01-15T16:45:00.000000Z"
  }
}
```

---

## 5. Generate AI Recipes

### Request
```http
POST /api/recipes/generate
Authorization: Bearer 2|xyz789abc012def345ghi678jkl901mno234pqr567stu890vwx
Content-Type: application/json

{
  "allow_suggestions_with_missing_ingredients": true,
  "portion_size": 2,
  "type": "main dish",
  "cooking_time": 45,
  "extra_instruction": "Please focus on high-protein meals that support muscle building goals"
}
```

### Response
```json
{
  "success": true,
  "data": [
    {
      "name": "Protein-Packed Chicken & Quinoa Bowl",
      "description": "High-protein muscle-building meal with complete amino acids",
      "time": 35,
      "servings": 2,
      "ingredients": [
        {
          "ing": "200g chicken breast, diced",
          "have": true
        },
        {
          "ing": "1 cup quinoa",
          "have": true
        },
        {
          "ing": "2 cups broccoli florets",
          "have": true
        },
        {
          "ing": "2 eggs, hard boiled",
          "have": true
        },
        {
          "ing": "2 tbsp olive oil",
          "have": true
        },
        {
          "ing": "1/4 cup Greek yogurt",
          "have": true
        }
      ],
      "instructions": [
        "Cook quinoa according to package instructions",
        "Season and sauté chicken in 1 tbsp olive oil until cooked through",
        "Steam broccoli for 5-7 minutes until tender",
        "Hard boil eggs and slice in half",
        "Combine quinoa, chicken, and broccoli in bowl",
        "Top with sliced eggs and a dollop of Greek yogurt",
        "Drizzle with remaining olive oil and season to taste"
      ]
    },
    {
      "name": "Greek Yogurt Chicken Power Bowl",
      "description": "Creamy protein bowl perfect for post-workout recovery",
      "time": 25,
      "servings": 2,
      "ingredients": [
        {
          "ing": "250g chicken breast, grilled",
          "have": true
        },
        {
          "ing": "1 cup brown rice, cooked",
          "have": true
        },
        {
          "ing": "1/2 cup Greek yogurt",
          "have": true
        },
        {
          "ing": "1 cup broccoli, steamed",
          "have": true
        },
        {
          "ing": "1 tbsp olive oil",
          "have": true
        },
        {
          "ing": "1 tsp garlic powder",
          "have": false
        }
      ],
      "instructions": [
        "Grill seasoned chicken breast for 6-7 minutes per side",
        "Steam broccoli until bright green and tender",
        "Mix Greek yogurt with garlic powder for sauce",
        "Serve sliced chicken over brown rice",
        "Add steamed broccoli on the side",
        "Top with yogurt sauce and drizzle with olive oil"
      ]
    },
    {
      "name": "Muscle-Building Egg & Veggie Scramble",
      "description": "Quick high-protein breakfast or lunch option",
      "time": 15,
      "servings": 2,
      "ingredients": [
        {
          "ing": "6 large eggs",
          "have": true
        },
        {
          "ing": "1 cup broccoli, chopped",
          "have": true
        },
        {
          "ing": "2 tbsp olive oil",
          "have": true
        },
        {
          "ing": "1/4 cup Greek yogurt",
          "have": true
        },
        {
          "ing": "Salt and pepper to taste",
          "have": false
        }
      ],
      "instructions": [
        "Heat 1 tbsp olive oil in large pan over medium heat",
        "Sauté broccoli for 3-4 minutes until tender",
        "Beat eggs with salt and pepper",
        "Pour eggs into pan with broccoli",
        "Scramble gently until eggs are just set",
        "Serve with a dollop of Greek yogurt on top"
      ]
    },
    {
      "name": "Quinoa Protein Power Salad",
      "description": "Complete protein salad with all essential amino acids",
      "time": 30,
      "servings": 2,
      "ingredients": [
        {
          "ing": "1 cup quinoa, cooked and cooled",
          "have": true
        },
        {
          "ing": "150g chicken breast, grilled and sliced",
          "have": true
        },
        {
          "ing": "2 hard-boiled eggs, chopped",
          "have": true
        },
        {
          "ing": "1 cup broccoli, blanched",
          "have": true
        },
        {
          "ing": "3 tbsp olive oil",
          "have": true
        },
        {
          "ing": "2 tbsp lemon juice",
          "have": false
        }
      ],
      "instructions": [
        "Cook quinoa and let cool completely",
        "Grill chicken breast and slice into strips",
        "Hard boil eggs and roughly chop",
        "Blanch broccoli in boiling water for 2 minutes",
        "Combine quinoa, chicken, eggs, and broccoli",
        "Whisk olive oil with lemon juice for dressing",
        "Toss salad with dressing and serve chilled"
      ]
    },
    {
      "name": "High-Protein Chicken Rice Bowl",
      "description": "Simple muscle-building meal with lean protein and complex carbs",
      "time": 40,
      "servings": 2,
      "ingredients": [
        {
          "ing": "300g chicken breast, cubed",
          "have": true
        },
        {
          "ing": "1 cup brown rice",
          "have": true
        },
        {
          "ing": "2 cups broccoli florets",
          "have": true
        },
        {
          "ing": "2 tbsp olive oil",
          "have": true
        },
        {
          "ing": "1/4 cup Greek yogurt",
          "have": true
        },
        {
          "ing": "Herbs and spices to taste",
          "have": false
        }
      ],
      "instructions": [
        "Cook brown rice according to package directions",
        "Season chicken cubes with preferred spices",
        "Heat olive oil in large skillet over medium-high heat",
        "Cook chicken for 8-10 minutes until golden and cooked through",
        "Steam broccoli until bright green and tender",
        "Serve chicken over rice with steamed broccoli",
        "Top with a dollop of Greek yogurt"
      ]
    }
  ]
}
```

---

## 6. Create Recipe (with Plan)

### Request
```http
POST /api/recipes
Authorization: Bearer 2|xyz789abc012def345ghi678jkl901mno234pqr567stu890vwx
Content-Type: application/json

{
  "name": "Protein-Packed Chicken & Quinoa Bowl",
  "description": "High-protein muscle-building meal with complete amino acids",
  "time": 35,
  "servings": 2,
  "ingredients": [
    {
      "ing": "200g chicken breast, diced",
      "have": true
    },
    {
      "ing": "1 cup quinoa",
      "have": true
    },
    {
      "ing": "2 cups broccoli florets",
      "have": true
    },
    {
      "ing": "2 eggs, hard boiled",
      "have": true
    }
  ],
  "instructions": [
    "Cook quinoa according to package instructions",
    "Season and sauté chicken in olive oil until cooked through",
    "Steam broccoli for 5-7 minutes until tender",
    "Hard boil eggs and slice in half",
    "Combine all ingredients in bowl and serve"
  ],
  "plan_name": "Tomorrow's Lunch",
  "plan_date": "2025-01-16T13:00:00Z"
}
```

### Response
```json
{
  "success": true,
  "data": {
    "recipe": {
      "id": 2,
      "user_id": 1,
      "name": "Protein-Packed Chicken & Quinoa Bowl",
      "description": "High-protein muscle-building meal with complete amino acids",
      "time": 35,
      "servings": 2,
      "ingredients": [
        {
          "ing": "200g chicken breast, diced",
          "have": true
        },
        {
          "ing": "1 cup quinoa",
          "have": true
        },
        {
          "ing": "2 cups broccoli florets",
          "have": true
        },
        {
          "ing": "2 eggs, hard boiled",
          "have": true
        }
      ],
      "instructions": [
        "Cook quinoa according to package instructions",
        "Season and sauté chicken in olive oil until cooked through",
        "Steam broccoli for 5-7 minutes until tender",
        "Hard boil eggs and slice in half",
        "Combine all ingredients in bowl and serve"
      ],
      "created_at": "2025-01-15T17:30:00.000000Z",
      "updated_at": "2025-01-15T17:30:00.000000Z"
    },
    "plan": {
      "id": 2,
      "name": "Tomorrow's Lunch",
      "date": "2025-01-16T13:00:00.000000Z",
      "recipe_id": 2,
      "user_id": 1,
      "created_at": "2025-01-15T17:30:00.000000Z",
      "updated_at": "2025-01-15T17:30:00.000000Z"
    }
  }
}
```

---

## 7. Delete Recipe

### Request
```http
DELETE /api/recipes/2
Authorization: Bearer 2|xyz789abc012def345ghi678jkl901mno234pqr567stu890vwx
```

### Response
```json
{
  "success": true,
  "data": {
    "message": "Recipe deleted successfully"
  }
}
```

---

## 8. Update Plan

### Request
```http
PUT /api/plans/1
Authorization: Bearer 2|xyz789abc012def345ghi678jkl901mno234pqr567stu890vwx
Content-Type: application/json

{
  "name": "Friday Night Dinner",
  "date": "2025-01-17T19:30:00Z"
}
```

### Response
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Friday Night Dinner",
    "date": "2025-01-17T19:30:00.000000Z",
    "recipe_id": 1,
    "user_id": 1,
    "created_at": "2025-01-15T12:20:00.000000Z",
    "updated_at": "2025-01-15T18:15:00.000000Z",
    "recipe": {
      "id": 1,
      "user_id": 1,
      "name": "Grilled Chicken with Roasted Vegetables",
      "description": "Healthy low-carb meal with lean protein and fiber-rich vegetables",
      "time": 35,
      "servings": 2,
      "ingredients": [
        {
          "ing": "300g chicken breast, sliced",
          "have": true
        },
        {
          "ing": "1 cup broccoli florets",
          "have": true
        }
      ],
      "instructions": [
        "Preheat oven to 200°C (400°F)",
        "Season chicken with salt and pepper",
        "Grill chicken for 6-7 minutes per side",
        "Serve hot"
      ],
      "created_at": "2025-01-15T12:15:00.000000Z",
      "updated_at": "2025-01-15T12:15:00.000000Z"
    }
  }
}
```

---

## 9. Create Plan

### Request
```http
POST /api/create/plan
Authorization: Bearer 2|xyz789abc012def345ghi678jkl901mno234pqr567stu890vwx
Content-Type: application/json

{
  "name": "Friday Night Dinner",
  "date": "2025-01-17T19:30:00Z",
  "recipe_id": 87,
}
```

### Response
```json
{
  "success": true,
  "data": {
    "id": 8,
    "name": "Friday Night Dinner",
    "date": "2025-01-17T19:30:00.000000Z",
    "recipe_id": 87,
    "user_id": 1,
    "created_at": "2025-01-15T12:20:00.000000Z",
    "updated_at": "2025-01-15T18:15:00.000000Z",
    "recipe": {
      "id": 1,
      "user_id": 1,
      "name": "Grilled Chicken with Roasted Vegetables",
      "description": "Healthy low-carb meal with lean protein and fiber-rich vegetables",
      "time": 35,
      "servings": 2,
      "ingredients": [
        {
          "ing": "300g chicken breast, sliced",
          "have": true
        },
        {
          "ing": "1 cup broccoli florets",
          "have": true
        }
      ],
      "instructions": [
        "Preheat oven to 200°C (400°F)",
        "Season chicken with salt and pepper",
        "Grill chicken for 6-7 minutes per side",
        "Serve hot"
      ],
      "created_at": "2025-01-15T12:15:00.000000Z",
      "updated_at": "2025-01-15T12:15:00.000000Z"
    }
  }
}
```

---

## 10. Delete Plan

### Request
```http
DELETE /api/plans/2
Authorization: Bearer 2|xyz789abc012def345ghi678jkl901mno234pqr567stu890vwx
```

### Response
```json
{
  "success": true,
  "data": {
    "message": "Plan deleted successfully"
  }
}
```

---

## 11. Ask Question About Recipe

### Request
```http
POST /api/questions
Authorization: Bearer 2|xyz789abc012def345ghi678jkl901mno234pqr567stu890vwx
Content-Type: application/json

{
  "recipe_id": 1,
  "question": "What can I substitute for broccoli if I don't like the taste?"
}
```

### Response
```json
{
  "success": true,
  "data": {
    "answer": "Great question! There are several delicious alternatives to broccoli that will work well in this recipe:\n\n1. **Cauliflower** - Similar texture and cooking time, milder flavor\n2. **Green beans** - Crisp texture, slightly sweet taste\n3. **Asparagus** - Elegant option with a unique flavor\n4. **Brussels sprouts** (halved) - Nutty flavor when roasted\n5. **Zucchini** (sliced) - Mild flavor, cooks quickly\n6. **Bell peppers** - Sweet and colorful\n\nFor roasting, use the same cooking time (about 20 minutes at 200°C/400°F). Just toss with olive oil, salt, and pepper like you would with the broccoli. Green beans and asparagus might need a minute or two less cooking time, so keep an eye on them!"
  }
}
```

---

## Error Response Examples

### Insufficient Tokens (Recipe Generation)
```json
{
  "success": false,
  "data": {
    "message": "Insufficient recipe tokens"
  }
}
```

### Insufficient Tokens (Question)
```json
{
  "success": false,
  "data": {
    "message": "Insufficient question tokens"
  }
}
```

### Validation Error
```json
{
  "success": false,
  "data": {
    "errors": {
      "email": [
        "The email field is required."
      ],
      "password": [
        "The password field is required."
      ]
    }
  }
}
```

### Unauthorized Access
```json
{
  "success": false,
  "data": {
    "message": "Unauthenticated."
  }
}
```

### Resource Not Found
```json
{
  "success": false,
  "data": {
    "message": "Recipe not found or unauthorized"
  }
}
```

*/
