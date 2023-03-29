<template>
    <div class="kanban-board">
        <div class="new-task-form">
            <input type="text" placeholder="Enter a new task" v-model="taskTitle">
            <button @click="addTask">Add</button>
            <div v-show="errorMessage">
                <span class="error-message">
                  {{ errorMessage }}
                </span>
            </div>
            <div v-show="successMessage">
                <span class="success-message">
                  {{ successMessage }}
                </span>
            </div>
        </div>
        <div class="board-row" v-for="(statuses, rowIndex) in rowColumns" :key="rowIndex">
            <div class="board-group">
                <div class="board-status" v-for="(status, statusIndex) in statuses" :key="statusIndex">
                    <h3 class="board-title">{{ status.title }}</h3>
                    <div class="board-task" v-for="(task, taskIndex) in status.tasks" :key="taskIndex"
                         @dragstart="dragStart(rowIndex, statusIndex, taskIndex)" draggable="true"
                         @dragover.prevent @drop="drop(rowIndex, statusIndex, taskIndex)">
                        <div class="task-content">
                            <p class="task-title">{{ task.title }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            taskTitle: '',
            newCardTitle: '',
            statuses: [],
            successMessage: '',
            errorMessage: '',
            draggingColumnIndex: -1,
            draggingCardIndex: -1,
            columns: []
        }
    },
    computed: {
        rowColumns() {
            let rowColumns = []
            for (let i = 0; i < this.statuses.length; i += 3) {
                rowColumns.push(this.statuses.slice(i, i + 3))
            }
            return rowColumns
        },
    },
    watch: {
        taskTitle() {
            this.errorMessage = ''
        }
    },

    mounted() {
        this.fetchTasks()
    },

    methods: {
        fetchTasks() {
            axios.get("/api/tasks")
                .then(res => {
                    this.statuses = res.data.data
                })
                .catch(err => {
                    console.log(err)
                });
        },
        addTask() {
            if (!this.taskTitle) {
                this.errorMessage = "The title field is required";
                return
            }
            let taskObj = {
                title: this.taskTitle,
                status_id: 1
            }
            axios.post("/api/tasks/create", taskObj)
                .then(res => {
                    this.statuses[0].tasks.unshift({title: this.taskTitle})
                    this.successMessage = "Task added successfully";
                    setTimeout(() => {
                        this.successMessage = ''
                    }, 3000)
                    this.taskTitle = ''
                })
                .catch(err => {
                    console.log(err)
                });
        },

        dragStart(rowIndex, statusIndex, taskIndex) {
            this.draggingColumnIndex = statusIndex;
            this.draggingCardIndex = taskIndex;
            const card = this.rowColumns[rowIndex][statusIndex].tasks[taskIndex];
            event.dataTransfer.setData('text/plain', JSON.stringify(card));
        },

        drop(rowIndex, statusIndex, taskIndex) {
            const card = JSON.parse(event.dataTransfer.getData('text/plain'));
            if (this.draggingCardIndex > -1) {
                this.rowColumns[this.draggingColumnIndex][this.draggingCardIndex].tasks.splice(this.draggingCardIndex, 1);
            }
            if (this.draggingColumnIndex !== statusIndex) {
                card.status_id = this.statuses[statusIndex].id;
                axios.put("/api/tasks/sync/" + card.id, card)
                    .then(res => {
                        this.rowColumns[rowIndex][statusIndex].tasks.splice(taskIndex, 0, card);
                        this.successMessage = "Task moved successfully";
                        setTimeout(() => {
                            this.successMessage = ''
                        }, 3000)
                    })
                    .catch(err => {
                        console.log(err)
                    });
            }
            this.draggingCardIndex = -1;
            this.draggingColumnIndex = -1;
        },
        dragOver(event) {
            event.preventDefault();
        },
    }
}

</script>

<style>

.new-task-form {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
}

.board-row {
    display: flex;
    justify-content: center;
    align-items: flex-start;
}

.board-group {
    width: calc(60% - 20px);
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.board-status {
    width: calc(33.33% - 20px);
    border: 1px solid #ccc;
    border-radius: 5px;
    margin: 0 10px;
    padding: 10px;
}

.board-title {
    text-align: center;
    padding: 10px;
    background: rgb(255, 99, 71);
}

.board-task {
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 10px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}

.task-content {
    flex: 1;
}

.task-title {
    text-align: center;
    cursor: move;
}

.success-message {
    color: green;
}

.error-message {
    color: red;
}
</style>
