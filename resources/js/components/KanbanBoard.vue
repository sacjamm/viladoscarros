<template>
    <div class="kanban-board">
        <div v-for="status in statuses" :key="status" class="kanban-column">
            <h2>{{ status }}</h2>
            <div 
                v-for="lead in leadsByStatus(status)" 
                :key="lead.id" 
                class="kanban-card"
                draggable="true"
                @dragstart="dragStart(lead)"
                @dragend="dragEnd"
                @dragover.prevent
                @drop="drop(lead, status)"
            >
                <p>{{ lead.name }}</p>
                <p>{{ lead.description }}</p>
                <p>{{ lead.email }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            leads: [],
            draggedLead: null,
            statuses: ['new', 'in_progress', 'won', 'lost'],
        };
    },
    mounted() {
        this.fetchLeads();
    },
    methods: {
        fetchLeads() {
            axios.get('/admin/leads')
                .then(response => {
                    this.leads = response.data;
                })
                .catch(error => console.log(error));
        },
        leadsByStatus(status) {
            return this.leads.filter(lead => lead.status === status);
        },
        dragStart(lead) {
            this.draggedLead = lead;
        },
        dragEnd() {
            this.draggedLead = null;
        },
        drop(targetLead, newStatus) {
            if (this.draggedLead && this.draggedLead.status !== newStatus) {
                this.draggedLead.status = newStatus;
                axios.put(`/admin/leads/${this.draggedLead.id}`, {
                    status: newStatus
                })
                .then(() => this.fetchLeads())
                .catch(error => console.log(error));
            }
        }
    }
};
</script>

<style>
.kanban-board {
    display: flex;
    gap: 20px;
}
.kanban-column {
    flex: 1;
    padding: 10px;
    background: #f4f4f4;
    border-radius: 5px;
}
.kanban-card {
    background: white;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    cursor: move;
}
</style>
