<template>
  <div class="plan-card card mb-2">
    <div class="card-body p-2">
      <div class="d-flex justify-content-between align-items-start">
        <div class="flex-grow-1" @click="$emit('view', plan)">
          <h6 class="mb-1 small">{{ plan.recipe?.name || 'Unknown Recipe' }}</h6>
          <p class="mb-1 text-muted small">{{ plan.name }}</p>
          <small class="text-muted">
            <i class="fas fa-clock me-1"></i>
            {{ formatTime(plan.date) }}
          </small>
        </div>
        <div class="plan-actions">
          <button
            @click.stop="editPlan"
            class="btn btn-outline-primary btn-sm me-1"
            :title="$t('Edit')"
          >
            <i class="fas fa-edit"></i>
          </button>
          <button
            @click.stop="$emit('delete', plan)"
            class="btn btn-outline-danger btn-sm"
            :title="$t('Delete')"
          >
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RecipePlanComponent',
  props: {
    plan: {
      type: Object,
      required: true
    }
  },
  emits: ['view', 'edit', 'delete'],
  setup(props, { emit }) {
    const formatTime = (dateString) => {
      const date = new Date(dateString)
      return date.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
      })
    }

    const editPlan = () => {
      // Simple inline editing - could be expanded to a modal
      const newName = prompt('Enter new plan name:', props.plan.name)
      const newDate = prompt('Enter new date/time (YYYY-MM-DD HH:MM):', props.plan.date)

      if (newName && newDate) {
        emit('edit', props.plan, {
          name: newName,
          date: new Date(newDate).toISOString()
        })
      }
    }

    return {
      formatTime,
      editPlan
    }
  }
}
</script>

<style scoped>
.plan-card {
  cursor: pointer;
  transition: all 0.2s ease;
  border: 1px solid #e9ecef;
}

.plan-card:hover {
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.plan-actions {
  opacity: 0;
  transition: opacity 0.2s ease;
}

.plan-card:hover .plan-actions {
  opacity: 1;
}
</style>
