<template>
  <div class="recipe-card" @click="$emit('view', plan)">
    <div class="recipe-card-inner">
      <!-- Recipe Header -->
      <div class="recipe-header">
        <div class="recipe-title-section">
          <h6 class="recipe-title">{{ plan.recipe?.name || 'Unknown Recipe' }}</h6>
          <p class="recipe-description">{{ plan.name }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="recipe-actions">
          <button
            @click.stop="editPlan"
            class="action-btn action-btn-plan"
            :title="$t('Edit')"
          >
            <i class="fas fa-edit"></i>
          </button>
          <button
            @click.stop="$emit('delete', plan)"
            class="action-btn action-btn-delete"
            :title="$t('Delete')"
          >
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>

      <!-- Recipe Meta Information -->
      <div class="recipe-meta">
        <div class="meta-item">
          <i class="fas fa-clock"></i>
          <span>{{ formatTime(plan.date) }}</span>
        </div>
      </div>

      <!-- Recipe Status Badge -->

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
:root {
  --white: #ffffffff;
  --cosmic-latte: #fff8e8ff;
  --black: #000000ff;
  --brown: #934b00ff;
  --brown-hover: #a55500;
  --success-green: #28a745;
  --warning-yellow: #ffc107;
}

.recipe-card {
  background: var(--white);
  border-radius: 16px;
  border: 2px solid rgba(147, 75, 0, 0.15);
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
  position: relative;
  height: 100%;
}

.recipe-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--brown), var(--brown-hover));
  opacity: 0;
  transition: opacity 0.3s ease;
}

.recipe-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(147, 75, 0, 0.15);
  border-color: var(--brown);
}

.recipe-card:hover::before {
  opacity: 1;
}

.recipe-card-inner {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  height: 100%;
}

.recipe-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
}

.recipe-title-section {
  flex: 1;
  min-width: 0;
}

.recipe-title {
  font-size: 1.125rem;
  font-weight: 700;
  color: var(--black);
  margin: 0 0 0.5rem 0;
  letter-spacing: -0.01em;
  line-height: 1.3;
}

.recipe-description {
  font-size: 0.875rem;
  color: var(--black);
  opacity: 0.7;
  margin: 0;
  line-height: 1.5;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.recipe-actions {
  display: flex;
  gap: 0.5rem;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.recipe-card:hover .recipe-actions {
  opacity: 1;
}

.action-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 10px;
  border: 2px solid;
  background: var(--white);
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  font-size: 0.875rem;
}

.action-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.action-btn-save {
  border-color: rgba(220, 53, 69, 0.3);
  color: #dc3545;
}

.action-btn-save:hover {
  background: #dc3545;
  color: var(--white);
  border-color: #dc3545;
}

.action-btn-plan {
  border-color: rgba(147, 75, 0, 0.3);
  color: var(--brown);
}

.action-btn-plan:hover {
  background: var(--brown);
  color: var(--white);
  border-color: var(--brown);
}

.action-btn-delete {
  border-color: rgba(220, 53, 69, 0.3);
  color: #dc3545;
}

.action-btn-delete:hover {
  background: #dc3545;
  color: var(--white);
  border-color: #dc3545;
}

.recipe-meta {
  display: flex;
  gap: 1.5rem;
  padding-top: 0.75rem;
  border-top: 1px solid rgba(147, 75, 0, 0.1);
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: var(--black);
  opacity: 0.7;
  font-weight: 500;
}

.meta-item i {
  color: var(--brown);
  font-size: 0.875rem;
}

.recipe-status {
  margin-top: auto;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border-radius: 10px;
  font-size: 0.875rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.status-ready {
  background: rgba(40, 167, 69, 0.1);
  color: var(--success-green);
  border: 1px solid rgba(40, 167, 69, 0.3);
}

.status-missing {
  background: rgba(255, 193, 7, 0.1);
  color: #d39e00;
  border: 1px solid rgba(255, 193, 7, 0.3);
}

@media (max-width: 768px) {
  .recipe-card-inner {
    padding: 1.25rem;
  }

  .recipe-title {
    font-size: 1rem;
  }

  .recipe-description {
    font-size: 0.8125rem;
  }

  .recipe-actions {
    opacity: 1;
  }

  .action-btn {
    width: 32px;
    height: 32px;
    font-size: 0.8125rem;
  }

  .meta-item {
    font-size: 0.8125rem;
  }

  .status-badge {
    font-size: 0.8125rem;
    padding: 0.4rem 0.875rem;
  }
}

@media (max-width: 480px) {
  .recipe-card-inner {
    padding: 1rem;
  }

  .recipe-meta {
    gap: 1rem;
  }
}
</style>
