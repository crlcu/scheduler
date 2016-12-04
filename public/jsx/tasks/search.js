import React from'react'
import ReactDOM from'react-dom'

import {Button, Input, Pagination, ProgressBar} from 'react-materialize'

class TasksRow extends React.Component {
    render() {
        const task = this.props.task;

        return (
            <tr>
                <td>
                    <a href={"/tasks/" + task.id } title="View history">{ task.name }</a>
                </td>
                <td>{ task['average_for_humans'] }</td>
                <td>
                    <span title={ task['cron_for_humans'] }>{ task['schedule'] }</span>
                </td>
                <td>{ task['next_due'] }</td>
                <td>
                    
                </td>
            </tr>
        )
    }
}

class TasksSearch extends React.Component {
    constructor(props) {
        super(props);
        
        this.state = {
            loading:        true,
            tasks:          [],
            current_page:   1,
            total:          1,
            per_page:       10,
        };

        this.loadTasks = this.loadTasks.bind(this);
        this.search = this.search.bind(this);
    }

    componentWillMount() {
        this.loadTasks();
    }

    loadTasks(page, search) {
        var self = this;

        self.setState({ loading: true });

        if (self.latestXHR) {
            self.latestXHR.abort();
        }

        self.latestXHR = $.ajax({
            url:        '/api/tasks',
            data:       {
                page:   page,
                search: search
            },
            dataType:   'json',
            success:    function(json) {
                self.setState({
                    loading:        false,
                    tasks:          json.data,
                    current_page:   json.current_page,
                    total:          json.total,
                    per_page:       json.per_page
                });
            }
        });
    }

    search(e) {
        this.loadTasks(1, e.target.value);
    }

    render() {
        var rows = this.state.tasks.map((task, i) =>
            <TasksRow task={ task } key={ i } />
        );

        return (
            <div className="widget">
                <div className="header indigo lighten-5">
                    <span className="title">Tasks</span>
                </div>
                <div className="content">
                    <table className="bordered highlight condensed">
                        <caption>
                            <div className="file-path-wrapper">
                                <Input name="q" s={12} onChange={ this.search } placeholder="Search ..." autoComplete="off" />
                            </div>
                        </caption>

                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Average duration</th>
                                <th>Schedule</th>
                                <th width="150px">Next due</th>
                                <th width="120px">
                                    <a href="/tasks" className="btn-floating waves-effect waves-light green right" title="Add">
                                        <i className="material-icons">add</i>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {rows}
                        </tbody>
                    </table>

                    { this.state.loading ?
                        <ProgressBar />
                        :
                        ''
                    }
                </div>
                <div className="footer indigo lighten-5">
                    <Pagination items={ Math.ceil(this.state.total / this.state.per_page) } activePage={ this.state.current_page } maxButtons={5} href={''} onSelect={ this.loadTasks } />
                </div>
            </div>
        )
    }
}

var app = ReactDOM.render(
    <TasksSearch />,
    document.getElementById('tasks')
);
