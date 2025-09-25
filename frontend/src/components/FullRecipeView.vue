<template>
  <div class="modal-backdrop d-flex align-items-center justify-content-center" @click="closeModal">
    <div class="modal-content-custom" @click.stop>
      <div class="card shadow-lg" style="width: 90vw; max-width: 800px; max-height: 90vh;">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ recipe.name }}</h5>
          <div class="header-actions">
            <button
              v-if="!isSaved"
              @click="$emit('save', recipe)"
              class="btn btn-outline-danger btn-sm me-2"
              :title="$t('Save')"
            >
              <i class="fas fa-heart me-1"></i>
              {{ $t('Save') }}
            </button>
            <button
              v-if="isSaved"
              @click="$emit('plan', recipe)"
              class="btn btn-outline-primary btn-sm me-2"
              :title="$t('Create Plan')"
            >
              <i class="fas fa-calendar-plus me-1"></i>
              {{ $t('Create Plan') }}
            </button>
            <button
              v-if="isSaved"
              @click="$emit('delete', recipe)"
              class="btn btn-outline-danger btn-sm me-2"
              :title="$t('Delete')"
            >
              <i class="fas fa-trash me-1"></i>
              {{ $t('Delete') }}
            </button>
            <button @click="$emit('close')" class="btn btn-outline-secondary btn-sm">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>

        <div class="card-body" style="overflow-y: auto;">
          <div class="row">
            <div class="col-md-8">
              <p class="text-muted mb-3">{{ recipe.description }}</p>

              <div class="recipe-meta mb-4">
                <div class="row">
                  <div class="col-4">
                    <div class="text-center p-3 bg-light rounded">
                      <i class="fas fa-clock text-primary fs-4"></i>
                      <div class="mt-2">
                        <strong>{{ recipe.time }}</strong>
                        <div class="small text-muted">{{ $t('minutes') }}</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="text-center p-3 bg-light rounded">
                      <i class="fas fa-users text-success fs-4"></i>
                      <div class="mt-2">
                        <strong>{{ recipe.servings }}</strong>
                        <div class="small text-muted">{{ $t('servings') }}</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="text-center p-3 bg-light rounded">
                      <i class="fas fa-check-circle text-success fs-4" v-if="readyToCook"></i>
                      <i class="fas fa-exclamation-triangle text-warning fs-4" v-else></i>
                      <div class="mt-2">
                        <strong v-if="readyToCook">{{ $t('Ready to cook') }}</strong>
                        <strong v-else>{{ missingIngredients }} missing</strong>
                        <div class="small text-muted">ingredients</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <h6 class="mb-3">
                <i class="fas fa-list-ul me-2"></i>
                {{ $t('Instructions') }}
              </h6>
              <ol class="list-group list-group-numbered mb-4">
                <li
                  v-for="(instruction, index) in recipe.instructions"
                  :key="index"
                  class="list-group-item"
                >
                  {{ instruction }}
                </li>
              </ol>

              <!-- Questions Section -->
              <div class="questions-section">
                <h6 class="mb-3">
                  <i class="fas fa-question-circle me-2"></i>
                  Ask About This Recipe
                </h6>

                <div class="input-group mb-3">
                  <input
                    v-model="questionText"
                    type="text"
                    class="form-control"
                    placeholder="Ask a question about this recipe..."
                    @keyup.enter="askQuestion"
                  >
                  <button
                    @click="askQuestion"
                    class="btn btn-primary"
                    :disabled="!questionText.trim() || askingQuestion"
                  >
                    <i v-if="askingQuestion" class="fas fa-spinner fa-spin"></i>
                    <i v-else class="fas fa-paper-plane"></i>
                  </button>
                </div>

                <div v-if="questions.length > 0" class="questions-list">
                  <div
                    v-for="(qa, index) in questions"
                    :key="index"
                    class="card mb-2"
                  >
                    <div class="card-body p-3">
                      <div class="question mb-2">
                        <strong><i class="fas fa-question me-1"></i> {{ qa.question }}</strong>
                      </div>
                      <div class="answer text-muted">
                        <i class="fas fa-reply me-1"></i> {{ qa.answer }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <h6 class="mb-3">
                <i class="fas fa-shopping-cart me-2"></i>
                {{ $t('Ingredients') }}
              </h6>
              <ul class="list-group">
                <li
                  v-for="(ingredient, index) in recipe.ingredients"
                  :key="index"
                  class="list-group-item d-flex justify-content-between align-items-center"
                  :class="getIngredientClass(ingredient)"
                >
                  <span>{{ getIngredientText(ingredient) }}</span>
                  <span>
                    <i v-if="getIngredientStatus(ingredient)" class="fas fa-check text-success"></i>
                    <i v-else class="fas fa-shopping-cart text-warning"></i>
                  </span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'

export default {
  name: 'FullRecipeView',
  props: {
    recipe: {
      type: Object,
      required: true
    },
    isSaved: {
      type: Boolean,
      default: false
    }
  },
  emits: ['close', 'save', 'delete', 'plan', 'ask-question'],
  setup(props, { emit }) {
    const questionText = ref('')
    const questions = ref([])
    const askingQuestion = ref(false)

    const readyToCook = computed(() => {
      if (!props.recipe.ingredients) return true
      return props.recipe.ingredients.every(ingredient =>
        typeof ingredient === 'object' ? ingredient.have : true
      )
    })

    const missingIngredients = computed(() => {
      if (!props.recipe.ingredients) return 0
      return props.recipe.ingredients.filter(ingredient =>
        typeof ingredient === 'object' ? !ingredient.have : false
      ).length
    })

    const getIngredientText = (ingredient) => {
      return typeof ingredient === 'object' ? ingredient.ing : ingredient
    }

    const getIngredientStatus = (ingredient) => {
      return typeof ingredient === 'object' ? ingredient.have : true
    }

    const getIngredientClass = (ingredient) => {
      const hasIngredient = typeof ingredient === 'object' ? ingredient.have : true
      return hasIngredient ? 'list-group-item-success' : 'list-group-item-warning'
    }

    const closeModal = () => {
      emit('close')
    }

    const askQuestion = async () => {
      if (!questionText.value.trim()) return

      askingQuestion.value = true
      try {
        const answer = await emit('ask-question', { question: questionText.value })
        questions.value.push({
          question: questionText.value,
          answer: answer
        })
        questionText.value = ''
      } catch (error) {
        console.error('Failed to ask question:', error)
      } finally {
        askingQuestion.value = false
      }
    }

    return {
      questionText,
      questions,
      askingQuestion,
      readyToCook,
      missingIngredients,
      getIngredientText,
      getIngredientStatus,
      getIngredientClass,
      closeModal,
      askQuestion
    }
  }
}
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1050;
}

.list-group-item-success {
  background-color: #d1edff;
  border-color: #b8e6ff;
}

.list-group-item-warning {
  background-color: #fff3cd;
  border-color: #ffeaa7;
}

.questions-section {
  border-top: 1px solid #dee2e6;
  padding-top: 1rem;
}
</style>
