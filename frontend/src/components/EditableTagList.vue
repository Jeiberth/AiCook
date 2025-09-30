<template>
  <div class="editable-list-container">
    <label class="form-label">{{ title }}</label>
    <div class="tags-container">
      <span v-for="(item, index) in items" :key="index" class="tag">
        <div style="margin-bottom: 3px;">{{ item }}</div>
        <button @click="removeItem(index)" class="remove-tag-btn" :title="$t('Remove')">
          <i class="fas fa-times" style="font-size: 12px;"></i>
        </button>
      </span>
      <span v-if="!items || items.length === 0" class="no-items-text">
        {{ $t('noItemsYet') }}
      </span>
    </div>
    <div class="add-item-form">
      <input
        v-model="newItem"
        type="text"
        class="form-input"
        :placeholder="placeholder"
        @keyup.enter="addItem"
      />
      <button @click="addItem" class="btn btn-secondary" :disabled="!newItem.trim()" style="width: 105px">
        <i class="fas fa-plus"></i>
        <span >{{ $t('Add') }}</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
  title: { type: String, required: true },
  items: { type: Array, required: true },
  placeholder: { type: String, default: 'Add new item...' },
});

const emit = defineEmits(['update:items']);

const newItem = ref('');

const addItem = () => {
  const trimmedItem = newItem.value.trim();
  if (trimmedItem && !props.items.includes(trimmedItem)) {
    const updatedItems = [...props.items, trimmedItem];
    emit('update:items', updatedItems);
    newItem.value = '';
  }
};

const removeItem = (indexToRemove) => {
  const updatedItems = props.items.filter((_, index) => index !== indexToRemove);
  emit('update:items', updatedItems);
};
</script>

<style scoped>
.editable-list-container {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.form-label {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--black);
  opacity: 0.8;
}

.tags-container {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  padding: 0.75rem;
  background: var(--cosmic-latte);
  border: 2px solid rgba(147, 75, 0, 0.1);
  border-radius: 12px;
  min-height: 50px;
}

.tag {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background-color: var(--brown);
  color: var(--white);
  padding: 0.3rem 0.8rem;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 500;
}

.remove-tag-btn {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: var(--white);
  border-radius: 50%;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.2s;
}

.remove-tag-btn:hover {
  background: rgba(0, 0, 0, 0.3);
}

.no-items-text {
  color: var(--black);
  opacity: 0.6;
  font-style: italic;
  font-size: 0.9rem;
}

.add-item-form {
  display: flex;
  gap: 0.5rem;
}

.add-item-form .form-input {
  flex-grow: 1;
}

.add-item-form .btn {
  padding: 0.8rem 1.2rem;
  flex-shrink: 0;
}

/* .add-item-form .btn span {
  display: none;
} */

@media (min-width: 768px) {
  .add-item-form .btn span {
    display: inline; /* Show text on larger screens */
  }
}
</style>




<style scoped>
/* Inherit same styling system from Register page */
.login-page {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--cosmic-latte) 0%, var(--white) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
}

.login-container {
  width: 100%;
  max-width: 500px;
  animation: slideUp 0.6s ease-out;
}

.login-card {
  @extend .register-card;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}
</style>

<style scoped>
:root {
  --white: #ffffffff;
  --cosmic-latte: #fff8e8ff;
  --black: #000000ff;
  --brown: #934b00ff;
}

.register-page {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--cosmic-latte) 0%, var(--white) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
}

.register-container {
  width: 100%;
  max-width: 800px;
  animation: slideUp 0.6s ease-out;
}

.register-card {
  background: var(--white);
  border-radius: 24px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
  padding: 3rem 2.5rem;
  border: 1px solid rgba(147, 75, 0, 0.1);
  position: relative;
  overflow: hidden;
}

.register-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--brown), #a55500);
}

.card-header {
  text-align: center;
  margin-bottom: 2.5rem;
}

.logo-section {
  margin-bottom: 0rem;
}

.logo-icon {
  font-size: 3rem;
  color: var(--brown);
  margin-bottom: 1rem;
}

.card-title {
  font-size: 2rem;
  font-weight: 700;
  color: var(--black);
  margin: 0 0 0.5rem 0;
  letter-spacing: -0.01em;
}

.card-subtitle {
  font-size: 1rem;
  color: var(--black);
  opacity: 0.7;
  margin: 0;
}

.progress-section {
  margin-bottom: 2.5rem;
}

.progress-bar {
  width: 100%;
  height: 6px;
  background: rgba(147, 75, 0, 0.2);
  border-radius: 3px;
  overflow: hidden;
  margin-bottom: 1rem;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, var(--brown), #a55500);
  border-radius: 3px;
  transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.step-indicator {
  text-align: center;
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--brown);
}

.step-content {
  min-height: 400px;
  animation: fadeInStep 0.4s ease-out;
}

.step-header {
  text-align: center;
  margin-bottom: 2rem;
}

.step-icon {
  font-size: 2.5rem;
  color: var(--brown);
  margin-bottom: 1rem;
}

.step-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--black);
  margin: 0 0 0.5rem 0;
}

.step-description {
  font-size: 1rem;
  color: var(--black);
  opacity: 0.7;
  margin: 0;
}

.selection-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.selection-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1.5rem 1rem;
  border: 2px solid rgba(147, 75, 0, 0.2);
  border-radius: 16px;
  background: var(--white);
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  text-align: center;
}

.selection-item:hover {
  border-color: var(--brown);
  transform: translateY(-2px);
  box-shadow: 0 8px 32px rgba(147, 75, 0, 0.1);
}

.selection-item.active {
  border-color: var(--brown);
  background: var(--cosmic-latte);
  transform: translateY(-2px);
  box-shadow: 0 8px 32px rgba(147, 75, 0, 0.2);
}

.item-icon {
  font-size: 1.5rem;
  color: var(--brown);
  margin-bottom: 0.75rem;
}

.item-label {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--black);
  text-transform: capitalize;
}

.custom-input-section {
  margin-bottom: 2rem;
}

.custom-input-wrapper {
  display: flex;
  gap: 0.5rem;
  max-width: 400px;
  margin: 0 auto;
}

.custom-input {
  flex: 1;
  padding: 1rem;
  border: 2px solid rgba(147, 75, 0, 0.2);
  border-radius: 12px;
  font-size: 0.9rem;
  background: var(--white);
  color: var(--black);
  transition: all 0.3s ease;
  outline: none;
}

.custom-input:focus {
  border-color: var(--brown);
  box-shadow: 0 0 0 4px rgba(147, 75, 0, 0.1);
}

.add-custom-btn {
  padding: 1rem;
  background: var(--brown);
  color: var(--white);
  border: none;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  min-width: 48px;
}

.add-custom-btn:hover {
  background: #a55500;
  transform: translateY(-1px);
}

.goal-selection {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.goal-item {
  padding: 2rem;
  border: 2px solid rgba(147, 75, 0, 0.2);
  border-radius: 20px;
  background: var(--white);
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  text-align: center;
}

.goal-item:hover {
  border-color: var(--brown);
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(147, 75, 0, 0.15);
}

.goal-item.active {
  border-color: var(--brown);
  background: var(--cosmic-latte);
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(147, 75, 0, 0.25);
}

.goal-icon {
  font-size: 2.5rem;
  color: var(--brown);
  margin-bottom: 1rem;
}

.goal-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--black);
  margin: 0 0 0.75rem 0;
}

.goal-description {
  font-size: 0.9rem;
  color: var(--black);
  opacity: 0.7;
  margin: 0;
  line-height: 1.5;
}

.personal-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.form-label {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--black);
  margin: 0;
  opacity: 0.8;
}

.form-input {
  padding: 1rem;
  border: 2px solid rgba(147, 75, 0, 0.2);
  border-radius: 12px;
  font-size: 1rem;
  background: var(--white);
  color: var(--black);
  transition: all 0.3s ease;
  outline: none;
}

.form-input:focus {
  border-color: var(--brown);
  box-shadow: 0 0 0 4px rgba(147, 75, 0, 0.1);
  transform: translateY(-1px);
}

.form-input::placeholder {
  color: var(--black);
  opacity: 0.5;
}

.error-message {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 1rem;
  background: rgba(220, 53, 69, 0.1);
  border: 1px solid rgba(220, 53, 69, 0.3);
  border-radius: 12px;
  color: #dc3545;
  font-size: 0.9rem;
  font-weight: 500;
}

.navigation-buttons {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 2.5rem;
  gap: 1rem;
}

.btn {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 2rem;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid transparent;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s;
}

.btn:hover:not(:disabled)::before {
  left: 100%;
}

.btn-primary {
  background: var(--brown);
  color: var(--white);
  box-shadow: 0 8px 32px rgba(147, 75, 0, 0.3);
}

.btn-primary:hover:not(:disabled) {
  background: #a55500;
  transform: translateY(-2px);
  box-shadow: 0 12px 40px rgba(147, 75, 0, 0.4);
}

.btn-secondary {
  background: var(--cosmic-latte);
  color: rgb(147 75 0);
  border-color: rgba(147, 75, 0, 0.3);
}

.btn-secondary:disabled {
    color: white;
}


.btn-secondary:hover {
  background: var(--brown);
  color: var(--white);
  border-color: var(--brown);
  transform: translateY(-2px);
  box-shadow: 0 8px 32px rgba(147, 75, 0, 0.3);
}

.login-link {
  text-align: center;
  margin-top: 2rem;
  font-size: 0.9rem;
  color: var(--black);
  opacity: 0.7;
}

.link {
  color: var(--brown);
  text-decoration: none;
  font-weight: 600;
  margin-left: 0.5rem;
  transition: all 0.3s ease;
}

.link:hover {
  text-decoration: underline;
  opacity: 0.8;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInStep {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .register-card {
    padding: 2rem 1.5rem;
    margin: 1rem;
  }

  .card-title {
    font-size: 1.75rem;
  }

  .logo-icon {
    font-size: 2.5rem;
  }

  .selection-grid {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 0.75rem;
  }

  .selection-item {
    padding: 1rem 0.75rem;
  }

  .goal-selection {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .goal-item {
    padding: 1.5rem;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .navigation-buttons {
    flex-direction: column;
    gap: 1rem;
  }

  .btn {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .register-card {
    padding: 1.5rem 1rem;
  }

  .step-content {
    min-height: 350px;
  }

  .selection-grid {
    grid-template-columns: 1fr 1fr;
  }

  .custom-input-wrapper {
    flex-direction: column;
  }

  .add-custom-btn {
    min-width: auto;
  }
}
</style>

