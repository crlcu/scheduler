var React = require('react');
var ReactDOM = require('react-dom');

import {Button, Input, Pagination, ProgressBar} from 'react-materialize';

var TasksRow = React.createClass({
    render: function() {
        var task = this.props.task;

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
        );
    }
});

var TasksTableBody = React.createClass({
    render: function() {
        var rows = this.props.tasks.map(task => {
            return <TasksRow task={ task } key={ task.id } />;
        });

        return (
            <tbody>
                { rows }
            </tbody>
        );
    }
});

var TasksSearch = React.createClass({
    getInitialState: function() {
        return {
            loading:        true,
            tasks:          [],
            current_page:   1,
            total:          1,
            per_page:       10,
        };
    },

    componentWillMount: function() {
        this.loadTasks();
    },

    loadTasks: function(page, search) {
        var self = this;

        self.setState({ loading: true });

        $.ajax({
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
    },

    search: function(e) {
        this.loadTasks(1, e.target.value);
    },

    render: function() {
        return (
            <div className="widget">
                <div className="header indigo lighten-5">
                    <span className="title">Tasks</span>
                </div>
                <div className="content">
                    <table className="bordered highlight condensed">
                        <caption>
                            <div className="file-field input-field">                                
                                <Button floating waves="light" icon="search" className="red lighten-1 right"  />

                                <div className="file-path-wrapper">
                                    <Input name="q" s={12} onChange={ this.search } placeholder="Search ..." autoComplete="off" />
                                </div>
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
                        <TasksTableBody tasks={ this.state.tasks } />
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
        );
    }
});

var app = ReactDOM.render(
    <TasksSearch />,
    document.getElementById('tasks')
);
