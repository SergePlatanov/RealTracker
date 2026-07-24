<script setup>
    defineProps({
        browser: String,
        version: String,
        reference: String
    })
</script>

<template>
    <div class="blocked-container">
        <div class="card">
        <h1>Обновите браузер</h1>
        <p class="warning">
            Ваша версия браузера устарела и не поддерживает современные стандарты безопасности.
        </p>
        
        <div class="info-box">
            Ваш браузер: <strong>{{ browser }} (v{{ version }})</strong>
        </div>

        <p class="action">
            {{ reference }}
        </p>
        </div>
    </div>
</template>

<style scoped>
    .blocked-container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        background-color: #f1f5f9;
        font-family: system-ui, sans-serif;
    }

    .card {
        background: white;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        max-width: 450px;
        text-align: center;
    }

    h1 { 
        color: #0f172a; 
        font-size: 1.75rem; 
        margin-bottom: 1rem; 
    }

    .warning { 
        color: #dc2626; 
        font-weight: 500; 
    }

    .info-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 0.75rem;
        border-radius: 6px;
        margin: 1.5rem 0;
        color: #334155;
    }

    .action { 
        color: #64748b; 
        font-size: 0.9rem; 
        line-height: 1.5; 
    }
</style>
