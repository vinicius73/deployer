@extends('layout')

@section('content')
    <div class="row">
        <div class="col-md-7">

            @if (!count($projects))
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{ Lang::get('dashboard.projects') }}</h3>
                    </div>
                    <div class="box-body">
                        <p>{{ Lang::get('dashboard.no_projects') }}</p>
                    </div>
                </div>
            @else
                @foreach ($projects as $group => $group_projects)
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{ $group }}</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ Lang::get('projects.name') }}</th>
                                    <th>{{ Lang::choice('dashboard.latest', 1) }}</th>
                                    <th>{{ Lang::get('dashboard.status') }}</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($group_projects as $project)
                                <tr id="project_{{ $project->id }}">
                                    <td><a href="{{ url('projects', ['id' => $project->id]) }}" title="View Details">{{ $project->name }}</a></td>
                                    <td>{{ $project->last_run ? $project->last_run->format('jS F Y g:i:s A') : 'Never' }}</td>
                                    <td>
                                        <span class="label label-{{ $project->css_class }}"><i class="fa fa-{{ $project->icon }}"></i> {{ $project->readable_status }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group pull-right">
                                            @if(isset($project->url))
                                            <a href="{{ $project->url }}" class="btn btn-default" title="{{ Lang::get('dashboard.site') }}" target="_blank"><i class="fa fa-globe"></i></a>
                                            @endif
                                            <a href="{{ url('projects', ['id' => $project->id]) }}" class="btn btn-default" title="{{ Lang::get('dashboard.view') }}"><i class="fa fa-info-circle"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <div class="col-md-5">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{ Lang::choice('dashboard.latest', 2) }}</h3>
                </div>
                <div class="box-body" id="timeline">
                    @if (!count($latest))
                        <p>{{ Lang::get('dashboard.no_deployments') }}</p>
                    @else
                    <ul class="timeline">
                        @foreach ($latest as $date => $deployments)
                            <li class="time-label">
                                <span class="bg-gray">{{ date('jS M Y', strtotime($date)) }}</span>
                            </li>

                            @foreach ($deployments as $deployment)
                            <li>
                                <i class="fa fa-{{ $deployment->icon }} bg-{{ $deployment->timeline_css_class }}"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {{ $deployment->started_at->format('H:i') }}</span>
                                    <h3 class="timeline-header"><a href="{{ url('projects', $deployment->project_id) }}">{{ $deployment->project->name }} </a> - <a href="{{ route('deployment', $deployment->id) }}">{{ Lang::get('dashboard.deployment_num', ['id' => $deployment->id]) }}</a> - <a href="{{ $deployment->branchURL() }}" target="_blank"><span class="label label-default">{{ $deployment->branch }}</span></a> {{ $deployment->readable_status }}</h3>

                                    @if (!empty($deployment->reason))
                                    <div class="timeline-body">
                                         {{ $deployment->reason }}
                                    </div>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                        @endforeach
                        <li>
                            <i class="fa fa-clock-o bg-gray"></i>
                        </li>
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop