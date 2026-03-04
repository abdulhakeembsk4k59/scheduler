<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Scheduler Assistant — A modern scheduling and productivity app built with Laravel">
    <title>Scheduler Assistant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="schedulerApp()" x-init="init()" :data-theme="theme">

<div class="app-layout">
    <!-- ═══════════════════ SIDEBAR ═══════════════════ -->
    <aside class="sidebar" :class="{ 'open': sidebarOpen }">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <svg style="width:1.25rem;height:1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <span class="sidebar-brand-text">Scheduler</span>
        </div>
        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Calendar</div>
            <button class="sidebar-nav-item" :class="{ active: view === 'month' }" @click="view = 'month'">
                <svg style="width:1.1rem;height:1.1rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z M4 10h16 M8 14h.01 M12 14h.01 M16 14h.01 M8 18h.01 M12 18h.01 M16 18h.01"></path></svg> 
                Month View
            </button>
            <button class="sidebar-nav-item" :class="{ active: view === 'week' }" @click="view = 'week'">
                <svg style="width:1.1rem;height:1.1rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg> 
                Week View
            </button>
            <button class="sidebar-nav-item" :class="{ active: view === 'day' }" @click="view = 'day'">
                <svg style="width:1.1rem;height:1.1rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                Day View
            </button>
            <button class="sidebar-nav-item" :class="{ active: view === 'agenda' }" @click="view = 'agenda'">
                <svg style="width:1.1rem;height:1.1rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> 
                Agenda
            </button>

            <div class="sidebar-section-label">Productivity</div>
            <button class="sidebar-nav-item" :class="{ active: view === 'focus' }" @click="view = 'focus'">
                <svg style="width:1.1rem;height:1.1rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M12 2a10 10 0 100 20 10 10 0 000-20z M12 2v2M22 12h-2M2 12H4M12 22v-2"></path></svg> 
                Focus Timer
            </button>
            <button class="sidebar-nav-item" :class="{ active: view === 'stats' }" @click="view = 'stats'">
                <svg style="width:1.1rem;height:1.1rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg> 
                Statistics
            </button>
            <button class="sidebar-nav-item" :class="{ active: view === 'manage' }" @click="view = 'manage'">
                <svg style="width:1.1rem;height:1.1rem;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> 
                Manage
            </button>
        </nav>
    </aside>

    <!-- ═══════════════════ MAIN CONTENT ═══════════════════ -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="header-nav-btn" @click="previousPeriod()">‹</button>
                <button class="header-nav-btn" @click="nextPeriod()">›</button>
                <button class="btn btn-ghost" @click="goToToday()">Today</button>
                <h1 class="header-title" x-text="headerTitle"></h1>
            </div>
            <div class="header-right">
                <button class="theme-toggle" @click="toggleTheme()" :title="theme === 'light' ? 'Switch to Dark Mode' : 'Switch to Light Mode'">
                    <span x-show="theme === 'light'">🌙</span>
                    <span x-show="theme === 'dark'">☀️</span>
                </button>
                <div class="view-tabs">
                    <button class="view-tab" :class="{ active: view === 'month' }" @click="view = 'month'">Month</button>
                    <button class="view-tab" :class="{ active: view === 'week' }" @click="view = 'week'">Week</button>
                    <button class="view-tab" :class="{ active: view === 'day' }" @click="view = 'day'">Day</button>
                    <button class="view-tab" :class="{ active: view === 'agenda' }" @click="view = 'agenda'">Agenda</button>
                </div>
                <button class="btn btn-primary" @click="openNewEventModal()">
                    <svg style="width:1.1rem;height:1.1rem;stroke-width:3px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Add Event
                </button>
            </div>
        </header>

        <!-- ═══════════════════ VIEWS ═══════════════════ -->
        <div class="calendar-container">

            <!-- ▸ MONTH VIEW -->
            <template x-if="view === 'month'">
                <div>
                    <div class="calendar-grid">
                        <template x-for="day in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']">
                            <div class="calendar-header-cell" x-text="day"></div>
                        </template>
                        <template x-for="cell in monthCells" :key="cell.dateStr">
                            <div class="calendar-cell"
                                 :class="{ 'other-month': !cell.currentMonth, 'today': cell.isToday }"
                                 @click="openNewEventModalForDate(cell.date)">
                                <div class="day-number" x-text="cell.day"></div>
                                <template x-for="ev in getEventsForDate(cell.dateStr)" :key="ev.id">
                                    <div class="event-pill"
                                         :class="[ev.category, ev.is_completed ? 'completed' : '']"
                                         @click.stop="openEditEventModal(ev)"
                                         x-text="ev.title">
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- ▸ WEEK VIEW -->
            <template x-if="view === 'week'">
                <div>
                    <div style="display:grid; grid-template-columns: 60px repeat(7, 1fr); gap: 0; border: 1px solid var(--border); border-radius: 12px; overflow: hidden;">
                        <div style="background: var(--bg-secondary); padding: 0.65rem; border-bottom: 1px solid var(--border);"></div>
                        <template x-for="d in weekDays" :key="d.dateStr">
                            <div style="background: var(--bg-secondary); padding: 0.65rem; text-align: center; border-bottom: 1px solid var(--border); border-left: 1px solid var(--border);">
                                <div style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted);" x-text="d.dayName"></div>
                                <div style="font-size: 1.1rem; font-weight: 700;" :style="d.isToday ? 'color: var(--accent)' : 'color: var(--text-primary)'" x-text="d.day"></div>
                            </div>
                        </template>
                        <template x-for="hour in 24" :key="hour">
                            <template x-for="col in 8" :key="col">
                                <div :style="col === 1
                                    ? 'background: var(--bg-secondary); padding: 0.25rem 0.5rem; font-size: 0.7rem; color: var(--text-muted); text-align: right; height: 50px; border-bottom: 1px solid var(--border);'
                                    : 'background: var(--bg-primary); height: 50px; border-bottom: 1px solid var(--border); border-left: 1px solid var(--border); position: relative; cursor: pointer;'"
                                    @click="col > 1 && openNewEventModalForDate(weekDays[col-2]?.date)">
                                    <template x-if="col === 1">
                                        <span x-text="(hour-1).toString().padStart(2,'0') + ':00'"></span>
                                    </template>
                                    <template x-if="col > 1">
                                        <template x-for="ev in getEventsForDateAndHour(weekDays[col-2]?.dateStr, hour-1)" :key="ev.id">
                                            <div class="event-pill" :class="[ev.category]"
                                                 style="position:absolute; inset: 2px; font-size: 0.65rem; padding: 2px 4px; border-radius: 4px;"
                                                 @click.stop="openEditEventModal(ev)" x-text="ev.title"></div>
                                        </template>
                                    </template>
                                </div>
                            </template>
                        </template>
                    </div>
                </div>
            </template>

            <!-- ▸ DAY VIEW -->
            <template x-if="view === 'day'">
                <div>
                    <div style="display: flex; flex-direction: column; gap: 1px; border: 1px solid var(--border); border-radius: 12px; overflow: hidden;">
                        <template x-for="hour in 24" :key="hour">
                            <div style="display: grid; grid-template-columns: 60px 1fr; min-height: 55px;"
                                 @click="openNewEventModalForDate(currentDate)">
                                <div style="background: var(--bg-secondary); padding: 0.25rem 0.5rem; font-size: 0.7rem; color: var(--text-muted); text-align: right; display: flex; align-items: flex-start; justify-content: flex-end; border-bottom: 1px solid var(--border);">
                                    <span x-text="(hour-1).toString().padStart(2,'0') + ':00'"></span>
                                </div>
                                <div style="background: var(--bg-primary); border-bottom: 1px solid var(--border); padding: 0.25rem; position: relative; cursor: pointer;">
                                    <template x-for="ev in getEventsForDateAndHour(formatDate(currentDate), hour-1)" :key="ev.id">
                                        <div class="event-pill" :class="[ev.category]"
                                             style="margin-bottom: 2px; padding: 0.3rem 0.5rem;"
                                             @click.stop="openEditEventModal(ev)" x-text="ev.title"></div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- ▸ AGENDA VIEW -->
            <template x-if="view === 'agenda'">
                <div class="agenda-list">
                    <template x-if="upcomingEvents.length === 0">
                        <div style="text-align: center; padding: 4rem 0; color: var(--text-muted);">
                            <div style="display:flex; justify-content:center; margin-bottom: 1rem; opacity: 0.5;">
                                <svg style="width:3.5rem;height:3.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <p style="font-size: 1rem; font-weight: 600;">No upcoming events</p>
                            <p style="font-size: 0.85rem; margin-top: 0.5rem;">Click "Add Event" to create one.</p>
                        </div>
                    </template>
                    <template x-for="group in agendaGroups" :key="group.dateStr">
                        <div class="agenda-day">
                            <div class="agenda-day-header" x-text="group.label"></div>
                            <template x-for="ev in group.events" :key="ev.id">
                                <div class="agenda-item" @click="openEditEventModal(ev)">
                                    <div class="agenda-item-time" x-text="formatTime(ev.start_date)"></div>
                                    <div class="agenda-item-dot" :class="ev.category"></div>
                                    <div class="agenda-item-title" :style="ev.is_completed ? 'text-decoration: line-through; opacity: 0.5;' : ''" x-text="ev.title"></div>
                                    <template x-if="ev.is_completed">
                                        <span class="agenda-item-badge badge-completed">✓ Done</span>
                                    </template>
                                    <template x-if="ev.priority === 'high' && !ev.is_completed">
                                        <span class="agenda-item-badge badge-priority-high">High</span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </template>

            <!-- ▸ FOCUS TIMER VIEW -->
            <template x-if="view === 'focus'">
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 4rem 2rem;">
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center; margin-bottom: 2.5rem;">
                            <button class="btn" :class="timerMode === 'work' ? 'btn-primary' : 'btn-secondary'" @click="setTimerMode('work')">Work (25m)</button>
                            <button class="btn" :class="timerMode === 'shortBreak' ? 'btn-primary' : 'btn-secondary'" @click="setTimerMode('shortBreak')">Short Break (5m)</button>
                            <button class="btn" :class="timerMode === 'longBreak' ? 'btn-primary' : 'btn-secondary'" @click="setTimerMode('longBreak')">Long Break (15m)</button>
                        </div>
                        <div class="timer-display" x-text="timerDisplay"></div>
                        <div class="timer-controls">
                            <button class="btn btn-primary" style="padding: 0.75rem 2.5rem; font-size: 1rem;" @click="toggleTimer()" x-text="timerRunning ? 'Pause' : (timerSeconds < timerDuration ? 'Resume' : 'Start')"></button>
                            <button class="btn btn-secondary" @click="resetTimer()">Reset</button>
                        </div>
                    </div>
                    <div style="margin-top: 3rem; text-align: center; color: var(--text-muted);">
                        <p style="font-size: 0.85rem;">Sessions completed today: <strong style="color: var(--accent);" x-text="pomodoroSessionsToday"></strong></p>
                    </div>
                </div>
            </template>

            <!-- ▸ STATISTICS VIEW -->
            <template x-if="view === 'stats'">
                <div>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value" x-text="events.length"></div>
                            <div class="stat-label">Total Events</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" x-text="events.filter(e => e.is_completed).length"></div>
                            <div class="stat-label">Completed</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" x-text="events.filter(e => !e.is_completed).length"></div>
                            <div class="stat-label">Pending</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" x-text="pomodoroStats.completed_sessions || 0"></div>
                            <div class="stat-label">Pomodoro Sessions</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" x-text="(pomodoroStats.total_work_time || 0) + 'm'"></div>
                            <div class="stat-label">Total Focus Time</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" x-text="completionRate + '%'"></div>
                            <div class="stat-label">Completion Rate</div>
                        </div>
                    </div>
                    <div style="padding: 0 1.5rem;">
                        <div class="manage-card">
                            <h3>Category Breakdown</h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem; margin-top: 1rem;">
                                <template x-for="cat in categoryStats" :key="cat.name">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; background: var(--bg-primary); border-radius: 8px;">
                                        <div class="agenda-item-dot" :class="cat.name"></div>
                                        <span style="font-size: 0.8rem; font-weight: 500; text-transform: capitalize;" x-text="cat.name"></span>
                                        <span style="margin-left: auto; font-size: 0.85rem; font-weight: 700; color: var(--accent);" x-text="cat.count"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- ▸ MANAGE VIEW -->
            <template x-if="view === 'manage'">
                <div class="manage-grid">
                    <div class="manage-card">
                        <h3>
                            <svg style="width:1.25rem;height:1.25rem;color:var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Import Schedule
                        </h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Paste a JSON array of events to import them in bulk.</p>
                        <textarea class="form-textarea" x-model="importJson" placeholder='[{"title":"Team Meeting","startDate":"2026-03-05T09:00:00","endDate":"2026-03-05T10:00:00","category":"work"}]' style="min-height: 120px; font-family: monospace; font-size: 0.78rem;"></textarea>
                        <div style="margin-top: 0.75rem; display: flex; gap: 0.5rem;">
                            <button class="btn btn-primary" @click="importSchedule()">Import</button>
                        </div>
                    </div>
                    <div class="manage-card">
                        <h3>
                            <svg style="width:1.25rem;height:1.25rem;color:var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Bulk Delete
                        </h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Delete all events or filter by category.</p>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <button class="btn btn-danger" @click="bulkDelete()">Delete All Events</button>
                            <template x-for="cat in ['work','personal','health','learning','finance','social']">
                                <button class="btn btn-secondary" @click="bulkDelete(cat)" style="text-transform: capitalize;" x-text="'Delete ' + cat"></button>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

        </div><!-- /calendar-container -->
    </div>
</div>

<!-- ═══════════════════ EVENT MODAL ═══════════════════ -->
<template x-if="modalOpen">
    <div class="modal-overlay" @click.self="modalOpen = false">
        <div class="modal-content">
            <div class="modal-header">
                <h3 x-text="editingEvent ? 'Edit Event' : 'New Event'"></h3>
                <button class="btn btn-ghost" @click="modalOpen = false" style="font-size: 1.2rem;">✕</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input class="form-input" type="text" x-model="eventForm.title" placeholder="Event title..." autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-textarea" x-model="eventForm.description" placeholder="Add a description..." rows="2"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input class="form-input" type="datetime-local" x-model="eventForm.start_date">
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Date</label>
                        <input class="form-input" type="datetime-local" x-model="eventForm.end_date">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select class="form-select" x-model="eventForm.category">
                            <option value="work">💼 Work</option>
                            <option value="personal">👤 Personal</option>
                            <option value="health">💚 Health</option>
                            <option value="learning">📚 Learning</option>
                            <option value="finance">💰 Finance</option>
                            <option value="social">🎉 Social</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Priority</label>
                        <select class="form-select" x-model="eventForm.priority">
                            <option value="high">🔴 High</option>
                            <option value="medium">🟡 Medium</option>
                            <option value="low">🟢 Low</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Timing Mode</label>
                        <select class="form-select" x-model="eventForm.timing_mode">
                            <option value="specific">⏰ Specific Time</option>
                            <option value="anytime">🔄 Anytime</option>
                            <option value="deadline">🎯 Deadline</option>
                        </select>
                    </div>
                    <div class="form-group" style="display: flex; align-items: flex-end; padding-bottom: 0.15rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem;">
                            <input type="checkbox" x-model="eventForm.is_recurring" style="accent-color: var(--accent);">
                            Recurring
                        </label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Daily Start Time</label>
                        <input class="form-input" type="time" x-model="eventForm.daily_start_time">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Daily End Time</label>
                        <input class="form-input" type="time" x-model="eventForm.daily_end_time">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <template x-if="editingEvent">
                    <button class="btn btn-danger" @click="deleteEvent(editingEvent.id)">Delete</button>
                </template>
                <template x-if="editingEvent && !editingEvent.is_completed">
                    <button class="btn btn-secondary" @click="toggleComplete(editingEvent.id)">✓ Complete</button>
                </template>
                <div style="flex: 1;"></div>
                <button class="btn btn-secondary" @click="modalOpen = false">Cancel</button>
                <button class="btn btn-primary" @click="saveEvent()">
                    <span x-text="editingEvent ? 'Update' : 'Create'"></span>
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Toast Container -->
<div class="toast-container">
    <template x-for="t in toasts" :key="t.id">
        <div class="toast" :class="t.type" x-text="t.message"
             x-init="setTimeout(() => toasts = toasts.filter(x => x.id !== t.id), 3000)">
        </div>
    </template>
</div>

<script>
function schedulerApp() {
    return {
        // State
        view: 'month',
        currentDate: new Date(),
        events: [],
        sidebarOpen: false,

        // Modal
        modalOpen: false,
        editingEvent: null,
        eventForm: {},

        // Timer
        timerMode: 'work',
        timerRunning: false,
        timerSeconds: 0,
        timerDuration: 25 * 60,
        timerInterval: null,
        pomodoroSessionsToday: 0,
        pomodoroStats: {},

        // Manage
        importJson: '',

        // Theme
        theme: localStorage.getItem('scheduler_theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),

        // Toast
        toasts: [],

        // Computed
        get headerTitle() {
            const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            if (this.view === 'day') {
                return `${months[this.currentDate.getMonth()]} ${this.currentDate.getDate()}, ${this.currentDate.getFullYear()}`;
            }
            return `${months[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
        },

        get monthCells() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const startOffset = firstDay.getDay();
            const cells = [];
            const today = new Date();
            const todayStr = this.formatDate(today);

            // Previous month days
            for (let i = startOffset - 1; i >= 0; i--) {
                const d = new Date(year, month, -i);
                cells.push({ date: d, dateStr: this.formatDate(d), day: d.getDate(), currentMonth: false, isToday: false });
            }
            // Current month days
            for (let d = 1; d <= lastDay.getDate(); d++) {
                const date = new Date(year, month, d);
                const dateStr = this.formatDate(date);
                cells.push({ date, dateStr, day: d, currentMonth: true, isToday: dateStr === todayStr });
            }
            // Next month days to fill grid
            const remaining = 42 - cells.length;
            for (let d = 1; d <= remaining; d++) {
                const date = new Date(year, month + 1, d);
                cells.push({ date, dateStr: this.formatDate(date), day: d, currentMonth: false, isToday: false });
            }
            return cells;
        },

        get weekDays() {
            const start = new Date(this.currentDate);
            start.setDate(start.getDate() - start.getDay());
            const days = [];
            const dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
            const today = this.formatDate(new Date());
            for (let i = 0; i < 7; i++) {
                const d = new Date(start);
                d.setDate(d.getDate() + i);
                days.push({
                    date: d,
                    dateStr: this.formatDate(d),
                    day: d.getDate(),
                    dayName: dayNames[d.getDay()],
                    isToday: this.formatDate(d) === today,
                });
            }
            return days;
        },

        get upcomingEvents() {
            return [...this.events].sort((a, b) => new Date(a.start_date) - new Date(b.start_date));
        },

        get agendaGroups() {
            const groups = {};
            const sorted = this.upcomingEvents;
            sorted.forEach(ev => {
                const dateStr = this.formatDate(new Date(ev.start_date));
                if (!groups[dateStr]) {
                    const d = new Date(ev.start_date);
                    groups[dateStr] = {
                        dateStr,
                        label: d.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }),
                        events: []
                    };
                }
                groups[dateStr].events.push(ev);
            });
            return Object.values(groups);
        },

        get completionRate() {
            if (this.events.length === 0) return 0;
            return Math.round((this.events.filter(e => e.is_completed).length / this.events.length) * 100);
        },

        get categoryStats() {
            const cats = ['work','personal','health','learning','finance','social'];
            return cats.map(name => ({
                name,
                count: this.events.filter(e => e.category === name).length
            })).filter(c => c.count > 0);
        },

        get timerDisplay() {
            const remaining = this.timerDuration - this.timerSeconds;
            const mins = Math.floor(remaining / 60);
            const secs = remaining % 60;
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        },

        // Methods
        async init() {
            await this.fetchEvents();
            await this.fetchPomodoroStats();
        },

        async fetchEvents() {
            try {
                const res = await fetch('/api/events');
                this.events = await res.json();
            } catch (e) {
                this.showToast('Failed to load events', 'error');
            }
        },

        async fetchPomodoroStats() {
            try {
                const res = await fetch('/api/pomodoro/stats');
                this.pomodoroStats = await res.json();
                this.pomodoroSessionsToday = this.pomodoroStats.completed_sessions || 0;
            } catch (e) { /* silent */ }
        },

        formatDate(date) {
            const y = date.getFullYear();
            const m = (date.getMonth() + 1).toString().padStart(2, '0');
            const d = date.getDate().toString().padStart(2, '0');
            return `${y}-${m}-${d}`;
        },

        formatTime(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        },

        formatDateTimeLocal(date) {
            const y = date.getFullYear();
            const m = (date.getMonth() + 1).toString().padStart(2, '0');
            const d = date.getDate().toString().padStart(2, '0');
            const h = date.getHours().toString().padStart(2, '0');
            const min = date.getMinutes().toString().padStart(2, '0');
            return `${y}-${m}-${d}T${h}:${min}`;
        },

        getEventsForDate(dateStr) {
            return this.events.filter(ev => {
                const evDate = this.formatDate(new Date(ev.start_date));
                return evDate === dateStr;
            });
        },

        getEventsForDateAndHour(dateStr, hour) {
            if (!dateStr) return [];
            return this.events.filter(ev => {
                const d = new Date(ev.start_date);
                return this.formatDate(d) === dateStr && d.getHours() === hour;
            });
        },

        previousPeriod() {
            const d = new Date(this.currentDate);
            if (this.view === 'month') d.setMonth(d.getMonth() - 1);
            else if (this.view === 'week') d.setDate(d.getDate() - 7);
            else d.setDate(d.getDate() - 1);
            this.currentDate = d;
        },

        nextPeriod() {
            const d = new Date(this.currentDate);
            if (this.view === 'month') d.setMonth(d.getMonth() + 1);
            else if (this.view === 'week') d.setDate(d.getDate() + 7);
            else d.setDate(d.getDate() + 1);
            this.currentDate = d;
        },

        goToToday() {
            this.currentDate = new Date();
        },

        resetEventForm() {
            const now = new Date();
            const later = new Date(now.getTime() + 60 * 60 * 1000);
            this.eventForm = {
                title: '',
                description: '',
                start_date: this.formatDateTimeLocal(now),
                end_date: this.formatDateTimeLocal(later),
                category: 'work',
                priority: 'medium',
                is_recurring: false,
                timing_mode: 'specific',
                daily_start_time: '',
                daily_end_time: '',
            };
        },

        openNewEventModal() {
            this.editingEvent = null;
            this.resetEventForm();
            this.modalOpen = true;
        },

        openNewEventModalForDate(date) {
            this.editingEvent = null;
            this.resetEventForm();
            const d = new Date(date);
            d.setHours(9, 0, 0, 0);
            const end = new Date(d.getTime() + 60 * 60 * 1000);
            this.eventForm.start_date = this.formatDateTimeLocal(d);
            this.eventForm.end_date = this.formatDateTimeLocal(end);
            this.modalOpen = true;
        },

        openEditEventModal(ev) {
            this.editingEvent = ev;
            this.eventForm = {
                title: ev.title,
                description: ev.description || '',
                start_date: this.formatDateTimeLocal(new Date(ev.start_date)),
                end_date: this.formatDateTimeLocal(new Date(ev.end_date)),
                category: ev.category,
                priority: ev.priority,
                is_recurring: ev.is_recurring,
                timing_mode: ev.timing_mode || 'specific',
                daily_start_time: ev.daily_start_time || '',
                daily_end_time: ev.daily_end_time || '',
            };
            this.modalOpen = true;
        },

        async saveEvent() {
            if (!this.eventForm.title.trim()) {
                this.showToast('Title is required', 'error');
                return;
            }

            const body = {
                ...this.eventForm,
                start_date: new Date(this.eventForm.start_date).toISOString(),
                end_date: new Date(this.eventForm.end_date).toISOString(),
                daily_start_time: this.eventForm.daily_start_time || null,
                daily_end_time: this.eventForm.daily_end_time || null,
            };

            try {
                if (this.editingEvent) {
                    const res = await fetch(`/api/events/${this.editingEvent.id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(body),
                    });
                    if (!res.ok) { const err = await res.json(); throw new Error(err.detail || err.message || 'Update failed'); }
                    this.showToast('Event updated successfully!', 'success');
                } else {
                    const res = await fetch('/api/events', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(body),
                    });
                    if (!res.ok) { const err = await res.json(); throw new Error(err.detail || err.message || 'Create failed'); }
                    this.showToast('Event created successfully!', 'success');
                }
                this.modalOpen = false;
                await this.fetchEvents();
            } catch (e) {
                this.showToast(e.message, 'error');
            }
        },

        async deleteEvent(id) {
            if (!confirm('Delete this event?')) return;
            try {
                await fetch(`/api/events/${id}`, { method: 'DELETE' });
                this.modalOpen = false;
                await this.fetchEvents();
                this.showToast('Event deleted', 'success');
            } catch (e) {
                this.showToast('Failed to delete', 'error');
            }
        },

        async toggleComplete(id) {
            try {
                const res = await fetch(`/api/events/${id}/toggle-complete`, { method: 'PATCH', headers: { 'Accept': 'application/json' } });
                if (!res.ok) { const err = await res.json(); throw new Error(err.detail || 'Failed'); }
                this.modalOpen = false;
                await this.fetchEvents();
                this.showToast('Event completed! 🎉', 'success');
            } catch (e) {
                this.showToast(e.message, 'error');
            }
        },

        async bulkDelete(category = null) {
            const msg = category ? `Delete all ${category} events?` : 'Delete ALL events?';
            if (!confirm(msg)) return;
            try {
                const url = category ? `/api/events/bulk?category=${category}` : '/api/events/bulk';
                const res = await fetch(url, { method: 'DELETE' });
                const data = await res.json();
                await this.fetchEvents();
                this.showToast(`Deleted ${data.deleted} events`, 'success');
            } catch (e) {
                this.showToast('Failed to delete', 'error');
            }
        },

        async importSchedule() {
            try {
                const schedule = JSON.parse(this.importJson);
                const res = await fetch('/api/import/schedule', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ schedule }),
                });
                const data = await res.json();
                await this.fetchEvents();
                this.showToast(`Imported ${data.total_imported} events (${data.total_errors} errors)`, data.total_errors > 0 ? 'error' : 'success');
                this.importJson = '';
            } catch (e) {
                this.showToast('Invalid JSON format', 'error');
            }
        },

        // Timer
        setTimerMode(mode) {
            this.timerMode = mode;
            this.timerRunning = false;
            this.timerSeconds = 0;
            clearInterval(this.timerInterval);
            if (mode === 'work') this.timerDuration = 25 * 60;
            else if (mode === 'shortBreak') this.timerDuration = 5 * 60;
            else this.timerDuration = 15 * 60;
        },

        toggleTimer() {
            if (this.timerRunning) {
                this.timerRunning = false;
                clearInterval(this.timerInterval);
            } else {
                this.timerRunning = true;
                this.timerInterval = setInterval(() => {
                    this.timerSeconds++;
                    if (this.timerSeconds >= this.timerDuration) {
                        this.timerRunning = false;
                        clearInterval(this.timerInterval);
                        this.completePomodoro();
                    }
                }, 1000);
            }
        },

        resetTimer() {
            this.timerRunning = false;
            this.timerSeconds = 0;
            clearInterval(this.timerInterval);
        },

        async completePomodoro() {
            try {
                await fetch('/api/pomodoro/sessions', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ mode: this.timerMode, duration: this.timerDuration, completed: true }),
                });
                this.pomodoroSessionsToday++;
                this.showToast('Session completed! 🎉', 'success');
                await this.fetchPomodoroStats();
            } catch (e) { /* silent */ }
        },

        // Theme
        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            localStorage.setItem('scheduler_theme', this.theme);
        },

        // Toast
        showToast(message, type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, message, type });
        },
    };
}
</script>
</body>
</html>
