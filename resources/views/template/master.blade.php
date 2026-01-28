<!DOCTYPE html>
<html lang="en">

@include("template.header")

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        @include("template.navbar")
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include("template.sidebar")

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield("content")
                </div>
            </section>
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
        @include("template.footer")

    </div>
    <!-- ./wrapper -->

    {{-- Scripts --}}
    @include("template.scripts")
</body>

</html>
