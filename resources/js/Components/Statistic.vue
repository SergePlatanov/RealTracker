<template>
    <div w-20>
        <Bar
            id="my-chart-id"
            :options="chartOptions"
            :data="chartData"
        />
    </div>
</template>

<script setup>
    import { ref, computed, onBeforeMount, onMounted, onBeforeUnmount, onUnmounted, nextTick } from 'vue';
    import { Bar } from 'vue-chartjs'
    import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

    ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

    const props= defineProps({
        label: String,
        dataset: Object,
    });

    const chartData = ref({
            labels: props.dataset.labels,
            datasets: [{
                axis: 'y',
                label: props.dataset.title,
                data: props.dataset.data,
                fill: false,
                backgroundColor: props.dataset.backgroundColor,
                borderColor: props.dataset.borderColor,
                borderWidth: 1
            }]
      })

    const chartOptions = ref({
            responsive: true,
            indexAxis: 'y',
            maintainAspectRatio: false, // ВАЖНО: отключает фиксированное соотношение
            scales: {
                x: {
                ticks: {
                    // Изменение размера шрифта для оси X
                    font: {
                    size: 12
                    }
                }
                },
                y: {
                ticks: {
                    // Изменение размера шрифта для оси Y
                    font: {
                    size: 12
                    }
                }
                }
            },
            plugins: {
                legend: {
                    display: false, // Отключает заголовок/легенду
                }
            }        
        })

    onBeforeMount(() => {
    });
</script>