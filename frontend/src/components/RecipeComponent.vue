<template>
  <div class="recipe-card card h-100" @click="$emit('click', recipe)">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <h6 class="card-title mb-0">{{ recipe.name }}</h6>
        <div class="recipe-actions">
          <button
            v-if="!isSaved"
            @click.stop="$emit('save', recipe)"
            class="btn btn-outline-danger btn-sm"
            :title="$t('Save')"
          >
            <i class="fas fa-heart"></i>
          </button>
          <button
            v-if="isSaved"
            @click.stop="$emit('plan', recipe)"
            class="btn btn-outline-primary btn-sm me-1"
            :title="$t('Create Plan')"
          >
            <i class="fas fa-calendar-plus"></i>
          </button>
          <button
            v-if="isSaved"
            @click.stop="$emit('delete', recipe)"
            class="btn btn-outline-danger btn-sm"
            :title="$t('Delete')"
          >
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>

      <p class="card-text small text-muted mb-2">{{ recipe.description }}</p>

      <div class="recipe-meta d-flex justify-content-between text-muted small">
        <span>
          <i class="fas fa-clock me-1"></i>
          {{ recipe.time }} {{ $t('minutes') }}
        </span>
        <span>
          <i class="fas fa-users me-1"></i>
          {{ recipe.servings }} {{ $t('servings') }}
        </span>
      </div>

      <div class="mt-2">
        <span
          v-if="readyToCook"
          class="badge bg-success"
        >
          <i class="fas fa-check me-1"></i>
          {{ $t('Ready to cook') }}
        </span>
        <span
          v-else
          class="badge bg-warning text-dark"
        >
          <i class="fas fa-exclamation-triangle me-1"></i>
          {{ missingIngredients }} {{ $t('missing ingredients') }}
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'

export default {
  name: 'RecipeComponent',
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
  emits: ['click', 'save', 'delete', 'plan'],
  setup(props) {
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

    return {
      readyToCook,
      missingIngredients
    }
  }
}
</script>

<style scoped>
.recipe-card {
  cursor: pointer;
  transition: all 0.2s ease;
}

.recipe-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.recipe-actions {
  opacity: 0;
  transition: opacity 0.2s ease;
}

.recipe-card:hover .recipe-actions {
  opacity: 1;
}
</style>
